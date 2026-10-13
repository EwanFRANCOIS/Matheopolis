<?php

namespace modules\models;

use Exception;
use PDO;
use _assets\includes\DatabaseConnection;

/**
 * Modèle de gestion de la connexion utilisateur.
 *
 * Interroge la base de données pour vérifier les identifiants
 * et authentifier l'utilisateur.
 */
class LoginModel
{
    /**
     * Initialise l'instance du modèle avec la connexion à la base de données.
     *
     * @param DatabaseConnection $connection Instance du gestionnaire de connexion BDD.
     */
    public function __construct(private DatabaseConnection $connection)
    {
    }

    /**
     * Récupère un utilisateur en base de données et vérifie son mot de passe.
     *
     * @param string $_login    Identifiant ou email de l'utilisateur.
     * @param string $Password Mot de passe en clair soumis par le formulaire.
     *
     * @return object|null Retourne l'objet de l'utilisateur si l'authentification réussit, null sinon.
     */
    public function getConnection(string $_login, string $Password): ?object
    {

        $statement = $this->connection->getConnection()->prepare(
            'SELECT id, login, password FROM users WHERE login = :login LIMIT 1;'
        );

        if (!$statement->execute([':login' => $_login])) {
            //throw new Exception('Le login est incorrect');
            return null;
        }

        $connection = $statement->fetch(PDO::FETCH_OBJ);
        if (!$connection || !password_verify($Password, $connection->password)) {
            //throw new Exception('Le mot de passe est incorrect');
            return null;
        }

        return $connection;
    }
}
