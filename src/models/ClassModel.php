<?php

namespace src\models;

use PDO;
use src\core\DatabaseConnection;


/**
 * Gestion de classes et associations d'élèves et profs.
 */
class ClassModel{
    private PDO $db;

    /**
     * @param DatabaseConnection $connection Instance de connexion à la base de données
     */

    public function __construct(private DatabaseConnection $connection)
    {
    }


    /**
     * Permet à l'enseignant de mettre à jour son code de classe.
     * @param int $teacherId Identifiant associé au professeur
     * @param string $codeClasse Code permettant la connexion à la bonne classe
     * @return bool True si la mise à jour a modifié au moins une ligne, false sinon
     */
    public function updateTeacherClassCode(int $teacherId, string $codeClasse): bool
    {
        $sql = "UPDATE users SET code_classe = :code WHERE id = :id AND role = 'teacher'";
        $statement = $this->connection->getConnection()->prepare($sql);

        return $statement->execute([
            ':code' => $codeClasse,
            ':id' => $teacherId
        ]);
    }

    /**
     * Récupère tous les élèves rattachés à un professeur spécifique. Les infos de l'élèves sont stockés dans un tableau
     * @param int $teacherId Identifiant associé au professeur
     * @return array Renvoie un tableau contenant les élèves associés a un professeur
     */
    public function getStudentsByTeacher(int $teacherId): array
    {
        $sql = "SELECT id, username, code_classe FROM users WHERE prof_id = :prof_id AND role = 'student'";
        $statement = $this->connection->getConnection()->prepare($sql);

        $statement->execute([':prof_id' => $teacherId]);
        //Permet de dire en gros "Transforme chaque ligne renvoyé par curseur (fetch) en tableau associatif"
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Permet à l'enseignant de créer directement un compte élève rattaché à sa classe.
    * @param int $teacherId Identifiant associé au professeur
    * @param string $pseudo Pseudonyme de l'élève
    * @param string $password Mot de passe de l'élève
    * @param string $codeClasse Code pour rejoindre la classe

    */
        
    public function createStudent(int $teacherId, string $pseudo, string $password, string $codeClasse): bool
    {
        $sql = "INSERT INTO users (username, password, role, prof_id, code_classe) VALUES (:username, :password, 'student', :prof_id, :code_classe)";
        $statement = $this->connection->getConnection()->prepare($sql);

        return $statement->execute([
            ':username' => $pseudo,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':prof_id' => $teacherId,
            ':code_classe' => $codeClasse
        ]);
    }

    /**
     * Nettoie et formate un identifiant unique sous la forme nom.prenom.
     */
    public function formatPseudo(string $nom, string $prenom): string
    {
        $chaine = mb_strtolower(trim($nom) . '.' . trim($prenom), 'UTF-8');

        $accents = [
            'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
            'à'=>'a','â'=>'a','ä'=>'a',
            'î'=>'i','ï'=>'i',
            'ô'=>'o','ö'=>'o',
            'ù'=>'u','û'=>'u','ü'=>'u',
            'ç'=>'c'
        ];
        $chaine = strtr($chaine, $accents);

        //Remplace les caractères spéciaux par des '-'
        $chaine = preg_replace('/[\s\'\-]+/', '-', $chaine);

        //Garde les autres caractères restants
        return preg_replace('/[^a-z0-9\.\-]/', '', $chaine);
    }

    public function importStudentsCsv(int $teacherId, string $codeClasse, string $csvFilePath)
    {
        if (($handle = fopen($csvFilePath, 'r')) === false) {
            throw new Exception("Impossible d'ouvrir le fichier CSV.");
        }

        $handle = fopen($csvFilePath, 'r');
        $pdo = $this->connection->getConnection();
        
        $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $insertStmt = $pdo->prepare('INSERT INTO users (username, password, role, prof_id, code_classe) VALUES (?, ?, "student", ?, ?)');

        $password = password_hash('Eleve123!', PASSWORD_DEFAULT);
        $count = 0;

        fgetcsv($handle, 100, ';');

        while (($row = fgetcsv($handle, 1000, ';')) !== false) {
            $nom = trim($row[0] ?? '');
            $prenom = trim($row[1] ?? '');

            if ($nom !== '' && $prenom !== '') {
                $basePseudo = $this->formatPseudo($nom, $prenom);
                $pseudo = $basePseudo;
                $i = 1;

                // ajoute -$i si doublon
                while ($checkStmt->execute([$pseudo]) && $checkStmt->fetchColumn() > 0) {
                    $i++;
                    $pseudo = "$basePseudo-$i";
                }

                $insertStmt->execute([$pseudo, $password, $teacherId, $codeClasse]);
                $count++;
            }
        }

        fclose($handle);
        return $count;
    }
}