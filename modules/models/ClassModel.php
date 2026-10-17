<?php

namespace modules\models;

use PDO;
use _assets\includes\DatabaseConnection;


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
        $sql = "UPDATE users SET code_classe = :code WHERE id = :id AND role = 'enseignant'";
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
        $sql = "SELECT id, pseudo, code_classe FROM users WHERE prof_id = :prof_id AND role = 'eleve'";
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
        $sql = "INSERT INTO users (pseudo, password, role, prof_id, code_classe) VALUES (:pseudo, :password, 'eleve', :prof_id, :code_classe)";
        $statement = $this->connection->getConnection()->prepare($sql);

        return $statement->execute([
            ':pseudo' => $pseudo,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':prof_id' => $teacherId,
            ':code_classe' => $codeClasse
        ]);
    }
}