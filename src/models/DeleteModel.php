<?php

namespace src\models;

use Exception;
use PDO;
use src\core\DatabaseConnection;
use src\core\ExceptionDatabaseController;

/**
 * Modèle de gestion de la suppression des comptes des utilisateurs.
 */
class DeleteModel
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
     * Vérifie le mot de passe et supprime le compte de l'utilisateur.
     *
     * @param string $_login    Identifiant ou email de l'utilisateur.
     * @param string $Password Mot de passe en clair soumis par le formulaire.
     *
     * @return bool Retourne true si la suppression a réussi, false sinon.
     */
    public function getDelete(string $_login, string $Password): ?bool
    {

        $statement = $this->connection->getConnection()->
        prepare('SELECT password FROM users WHERE email = :login OR username = :login LIMIT 1;');
        if (!$statement->execute([':login' => $_login])) {
            throw new ExceptionDatabaseController('Le mot de passe est incorrect');
        }
        $connection = $statement->fetch(PDO::FETCH_OBJ);
        if (!$connection || !password_verify($Password, $connection->password)) {
            return false;
        }

        $deleteStatement = $this->connection->getConnection()->prepare('DELETE FROM users WHERE email = :login OR username = :login;');
        return $deleteStatement->execute([':login' => $_login]);
    }
}
