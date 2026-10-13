<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\models\SignupModel;
use includes\ExceptionDatabaseController;
use PDOException;
use Exception;

/**
 * Contrôleur gérant l'inscription des utilisateurs.
 *
 * Traite la soumission du formulaire d'inscription, vérifie la cohérence
 * des données saisies et fait appel au modèle pour enregistrer le compte.
 */
class SignupController
{
    /**
     * Exécute le traitement du formulaire et affiche la vue associée.
     *
     * @return void
     */
    public function execute(): void
    {

        session_start();

        $error = null;

        if (isset($_POST['action']) && !empty($_POST['action'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_login = $_POST['email'];
                $Password = $_POST['password'];
                $_verifPassword = $_POST['verif'];
                $_action = $_POST['action'];

                if (empty($_login) || empty($Password) || empty($_verifPassword)) {
                    $error = 'Veuillez remplir le formulaire';
                } elseif ($_verifPassword !== $Password) {
                    $error = 'Les mots de passe ne correspondent pas';
                } elseif ($_action === 'inscription') {
                    try {
                        $signupModel = new SignupModel(DatabaseConnection::getInstance());
                        $signup = $signupModel->getSignup($_login, $Password);
                        $_SESSION['email'] = $_login;
                        $_SESSION['password'] = $Password;
                        header('Location: index.php?success=true');
                        exit();
                        # Les | permettent juste de reunnir 3 catch en 1 seul pour eviter d'en avoir 3
                    } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                        $error = 'Ce compte existe déjà';
                    }
                } else {
                    $error = 'Veuillez remplir le formulaire';
                }
            }
        }

        (new \modules\views\SignupView())->show($error);
    }
}
