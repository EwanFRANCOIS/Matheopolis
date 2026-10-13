<?php

namespace _assets\includes;

use PDO; // C'est une class global pour la connexion a la bdd
use PDOException;

class DatabaseConnection
{
    private ?PDO $connection = null;
    # connection prsk on appelle getConnection dans le cours : c'est ce qui contient les param de la pdo
    private static ?DatabaseConnection $instance = null;
    # instance prsk on appelle getInstance aussi : ça permet de rester sur la meme session

    private function __construct()
    {
        $envPath = __DIR__ . "/../../.env"; // Recupp le chemin absolus du fichier .env
        if (file_exists($envPath)) {
            $lines = file($envPath);
            // Vérification pour PHPStan : s'assurer que file() n'a pas retourné false
            if ($lines !== false) {
                foreach ($lines as $line) {
                    list($name, $value) = explode('=', $line, 2); // On separe chaque = par 2 espace
                    $name = trim($name); // trim() supprime juste les caracteres speciaux
                    $value = trim($value);

                    // Verification si la variable n'existe pas deja pour eviter de tout casser
                    if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                        /*
                        putenv permet de modifier ou creer une variable global au niveau PHP
                        sprintf fabrique une string (%s pour string)
                        On fait ca car en gros, putenv ne peut recup qu'une seul string on met donc
                        tout dans une seul string d'ou le %s=%)
                        */
                        putenv(sprintf('%s=%s', $name, $value));
                        $_ENV[$name] = $value;
                        $_SERVER[$name] = $value;
                    }
                }
            }
        }
        try { # ça c comme dans le cours, hesitez pas a vous co avec les id pour voir la bdd
            $host = getenv('DB_HOST'); // On recup via le .env avec getenv
            $dbname = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $password = getenv('DB_PASS');

            // On remplace le 'false' potentiel par 'null' pour respecter la signature attendue par PDO
            $userParam = ($user !== false) ? $user : null;
            $passwordParam = ($password !== false) ? $password : null;

            $dsn = "mysql:host={$host};dbname={$dbname}";
            $this->connection = new PDO($dsn, $userParam, $passwordParam);
            $this->connection->exec('SET CHARACTER SET utf8');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    #vérifie si l'instance ( donc l'objet et la connexion en cours )
    # est null pour en creer une auto dans le controller soit renvoyer celle courante
    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    #renvoie juste la co courante qui peut etre nulle sans __construct
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            throw new \RuntimeException('Connexion non initialisée.');
        }

        return $this->connection;
    }
}
