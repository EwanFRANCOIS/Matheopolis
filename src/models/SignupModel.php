<?php

/* ref */

namespace src\models;

use _assets\includes\DatabaseConnection;
use Exception;
use includes\ExceptionDatabaseController;

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
     * @return bool Retourne true si l'inscription a réussi, false sinon.
     */
    public function getSignup(string $_login, string $_password, bool $_isTeacher): bool
    {
        $passwordHash = password_hash($_password, PASSWORD_DEFAULT);
        $role = $_isTeacher ? 'teacher' : 'unknown';
        $statement = $this->connection->getConnection()->prepare(
            "INSERT INTO users (email, password, role, reset_code, expiration_date) VALUES (:login, :password, :role, NULL, NULL);"
        );
        if (!$statement->execute([
            ':login' => $_login,
            ':password' => $passwordHash,
            ':role' => $role
        ])) {
            throw new ExceptionDatabaseController('Le mot de passe ou le login est incorrect');
        }

        return true;
    }
}
