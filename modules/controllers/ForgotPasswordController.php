<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\models\ForgotPasswordModel;
use modules\views\ForgotPasswordView;

/**
 * Contrôleur gérant le flux de réinitialisation de mot de passe.
 */
class ForgotPasswordController
{
    /**
     * Exécute le traitement des requêtes GET et POST pour la réinitialisation du mot de passe.
     *
     * @return void
     */

    public function execute(): void
    {
        $model = new ForgotPasswordModel(DatabaseConnection::getInstance());
        $messageError = null;
        $token = null;


    // On traite les données mis en post
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //On verif le type d'action
            if (isset($_POST['action_type'])) {
          //1 : Soumission du nouveau mdp
                if ($_POST['action_type'] === 'reset_password') {
                // Recup du token
                    if (isset($_POST['token'])) {
                        $token = $_POST['token'];
                    } else {
                        $token = '';
                    }

                //Recup du mdp
                    if (isset($_POST['password'])) {
                        $newPassword = $_POST['password'];
                    } else {
                        $newPassword = '';
                    }


                    //verif et maj du mdp
                    if (!empty($token) && !empty($newPassword)) {
                        $success = $model->verifyTokenAndChangePassword($token, $newPassword);

                        if ($success) {
                            header('Location: index.php?action=login&reset=success');
                            exit();
                        } else {
                            $messageError = "Lien invalide ou expiré.";
                        }
                    } else {
                        $messageError = "Veuillez remplir tous les champs nécessaires.";
                    }
                }



          //2 : Demande d'envoi de l'e-mail
                if ($_POST['action_type'] === 'request_reset') {
                    if (isset($_POST['email'])) {
                        $email = $_POST['email'];
                    } else {
                        $email = '';
                    }

                    if (!empty($email)) {
                        $model->sendMail($email);
                        header('Location: index.php?action=forgot_pwd&mail=sent');
                        exit();
                    } else {
                        $messageError = "Veuillez saisir votre adresse email.";
                    }
                }
            }
        }


    //Recup du token pour vue
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
           //dans le cas ou le token est dans le post
        } elseif (isset($_POST['token'])) {
            $token = $_POST['token'];
        }


        //Appel de vue
        (new ForgotPasswordView())->show($token, $messageError);
    }
}
