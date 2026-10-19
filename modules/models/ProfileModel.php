<?php

namespace modules\models;

use Exception;
use PDO;
use _assets\includes\DatabaseConnection;
use includes\ExceptionDatabaseController;

/**
 * Modèle de récuperation du type des comptes utilisateurs.
 */
class ProfileModel
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
     * Récupère le type de profil d'un utilisateur.
     *
     * @param string $_login    Identifiant ou email de l'utilisateur.
     *
     * @return String|null Retourne le type de compte si trouvé, null sinon.
     */
    public function getProfileType(string $_login): ?bool
    {

        $statement = $this->connection->getConnection()->prepare('SELECT role FROM users WHERE email = :login OR username = :login LIMIT 1;');
        $result = $statement->execute([':login' => $_login]);
        if (!$result) {
            throw new ExceptionDatabaseController('Erreur lors de la récupération du type de compte');
        }
        $profileType = $statement->fetch(PDO::FETCH_OBJ);
        return $profileType ? $profileType->role : null;
    }
}
