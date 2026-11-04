<?php

/* ref */

namespace src\models;

use src\core\DatabaseConnection;
use src\core\ExceptionDatabaseController;
use PDO;

/**
 * Modèle gérant la persistance des données d'inscription en base de données.
 */

class SignupModel
{
    /**
     * Initialise le modèle avec la connexion à la base de données.
     *
     * @param DatabaseConnection $connection Instance de connexion PDO encapsulée.
     */

    public function __construct(private DatabaseConnection $connection)
    {
    }



    /**
     * Crée un nouvel utilisateur en BDD après hachage de son mot de passe.
     *
     * @param string $_login Adresse email ou identifiant de l'utilisateur.
     * @param string $_password Mot de passe en clair à hacher.
     * @param bool $_isTeacher Indique si l'utilisateur est un enseignant.
     *
     * @throws ExceptionDatabaseController Si la requête d'insertion échoue.
     * @return object Utilisateur créé.
     */
    public function getSignup(string $_login, string $_password, bool $_isTeacher): object
    {
        $passwordHash = password_hash($_password, PASSWORD_DEFAULT);
        $role = $_isTeacher ? 'teacher' : 'unknown';
        $statement = $this->connection->getConnection()->prepare(
            "INSERT INTO users (email, password, role) VALUES (:login, :password, :role);"
        );
        if (!$statement->execute([
            ':login' => $_login,
            ':password' => $passwordHash,
            ':role' => $role
        ])) {
            throw new ExceptionDatabaseController('Le mot de passe ou le login est incorrect');
        }

        $statementConnexion = $this->connection->getConnection()->prepare(
            'SELECT id, email, role FROM users WHERE id = :id LIMIT 1'
        );

        if (!$statementConnexion->execute([
            ':id' => $this->connection->getConnection()->lastInsertId()
        ])) {
            throw new ExceptionDatabaseController('Le compte n’a pas pu être récupéré après sa création.');
        }

        $signup = $statementConnexion->fetch(PDO::FETCH_OBJ);
        if (!$signup) {
            throw new ExceptionDatabaseController('Le compte n’a pas pu être récupéré après sa création.');
        }

        return $signup;
    }
}
