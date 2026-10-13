<?php

namespace modules\models;

use Exception;
use includes\ExceptionDatabaseController;
use PDO;
use _assets\includes\DatabaseConnection;

/**
 * Gestion du processus de réinitialisation de mot de passe en base de données.
 */
class ForgotPasswordModel
{
    /**
     * @param DatabaseConnection $connection Instance de connexion à la base de données
     */


    public function __construct(private DatabaseConnection $connection)
    {
    }


    /**
     * Enregistre un token à usage unique valide pendant 15 minutes.
     *
     * @param string $email Adresse email associée au compte
     * @param string $token Empreinte aléatoire unique
     * @return bool True si la mise à jour a modifié au moins une ligne, false sinon
     * @throws Exception En cas d'erreur lors de l'exécution de la requête préparée
     */

    private function saveTokenInDataBase(string $email, string $token): bool
    {
        $sql = "UPDATE users SET reset_code = :token, 
        expiration_date = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE login = :email";
        $statement = $this->connection->getConnection()->prepare($sql);

        if (!$statement->execute([':token' => $token, ':email' => $email])) {
            throw new ExceptionDatabaseController("Erreur de base de données.");
        }

        return $statement->rowCount() > 0;
    }



    /**
     * Génère un token et expédie le courriel de réinitialisation.
     *
     * @param string $to Adresse email du destinataire
     * @return bool Toujours true pour éviter l'énumération de comptes
     */
    public function sendMail(string $to): bool
    {
        $token = bin2hex(random_bytes(32));

        if (!$this->saveTokenInDataBase($to, $token)) {
            return true;
        }


        $subject = 'Réinitialisez votre mot de passe';


    // Attention, le lien va peut-etre devoir etre changé, le but est de créer un lien unique pour changer de mdp
        $link = 'https://ninamc.alwaysdata.net/index.php?action=forgot_pwd&token=' . $token;
        $message = "Bonjour,\n\n voici votre lien de récupération de mot de passe : \n" . $link;

    // En-têtes obligatoires / recommandés
        $headers = [
        'From' => 'no-reply@ninamc.alwaysdata.net',
        'Reply-To' => 'no-reply@ninamc.alwaysdata.net',
        'Content-Type' => 'text/plain; charset=utf-8',
        'X-Mailer' => 'PHP/' . phpversion()
        ];

    // Envoi
        return mail($to, $subject, $message, $headers);
    }


    /**
     * Vérifie la validité du token et met à jour le mot de passe de l'utilisateur.
     *
     * @param string $token Empreinte aléatoire unique à vérifier
     * @param string $newPassword Nouveau mot de passe
     * @return bool True si le token est valide et le mot de passe mis à jour, false si le token est invalide ou expiré
     * @throws Exception En cas d'erreur lors de l'exécution de la requête préparée
     */

    public function verifyTokenAndChangePassword(string $token, string $newPassword): bool
    {
    // recherche de l'utilisateur associé au token dans la BDD
        $sql = "SELECT login FROM users 
                WHERE reset_code = :token 
                  AND reset_code IS NOT NULL 
                  AND expiration_date > NOW()";
        $statement = $this->connection->getConnection()->prepare($sql);

        if (!$statement->execute([':token' => $token])) {
            throw new ExceptionDatabaseController("Erreur de base de données.");
        }


        if ($user = $statement->fetch(PDO::FETCH_OBJ)) {
            //Hashage du mdp
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            //mtn on fait la maj de la bdd et on détruit le token
            $updateSql = "UPDATE users 
                          SET password = :password, 
                              reset_code = NULL, 
                              expiration_date = NULL 
                          WHERE login = :login";
            $updateStmt = $this->connection->getConnection()->prepare($updateSql);

            if (!$updateStmt->execute([':password' => $passwordHash, ':login' => $user->login])) {
                throw new ExceptionDatabaseController("Erreur de base de données.");
            }

            return true;
        }
        return false;
    }
}
