<?php

namespace src\core;

use PDO; // C'est une class global pour la connexion a la bdd
use PDOException;
use src\core\Env;

class DatabaseConnection
{
    private ?PDO $connection = null;
    # connection prsk on appelle getConnection dans le cours : c'est ce qui contient les param de la pdo
    private static ?DatabaseConnection $instance = null;
    # instance prsk on appelle getInstance aussi : ça permet de rester sur la meme session

    private function __construct()
    {
        Env::charger(__DIR__ . '/../../.env');

        try { # ça c comme dans le cours, hesitez pas a vous co avec les id pour voir la bdd
            $host = Env::get('DB_HOST');
            $dbname = Env::get('DB_NAME');
            $user = Env::get('DB_USER');
            $password = Env::get('DB_PASS');

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
