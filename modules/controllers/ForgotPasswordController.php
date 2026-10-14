<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\core\Request;
use modules\core\Response;
use modules\core\View;
use modules\models\ForgotPasswordModel;
use modules\views\ForgotPasswordView;

/**
 * Contrôleur gérant le flux de réinitialisation de mot de passe.
 */
class ForgotPasswordController
{
    public function __construct(private readonly View $view)
    {
    }

    /**
     * Exécute le traitement des requêtes GET et POST pour la réinitialisation du mot de passe.
     *
     * @return void
     */

    public function execute(Request $request): Response
    {
        $model = new ForgotPasswordModel(DatabaseConnection::getInstance());
        $messageError = null;
        $token = null;


    // On traite les données mis en post
        if ($request->method == 'POST') {
        //On verif le type d'action
            if ($request->post('action_type') !== null) {
          //1 : Soumission du nouveau mdp
                if ($request->post('action_type') === 'reset_password') {
                // Recup du token
                    if ($request->post('token') !== null) {
                        $token = $request->post('token');
                    } else {
                        $token = '';
                    }

                //Recup du mdp
                    if ($request->post('password') !== null) {
                        $newPassword = $request->post('password');
                    } else {
                        $newPassword = '';
                    }


                    //verif et maj du mdp
                    if (!empty($token) && !empty($newPassword)) {
                        $success = $model->verifyTokenAndChangePassword($token, $newPassword);

                        if ($success) {
                            return Response::redirect('index.php?action=login&reset=success');
                        } else {
                            $messageError = "Lien invalide ou expiré.";
                        }
                    } else {
                        $messageError = "Veuillez remplir tous les champs nécessaires.";
                    }
                }



          //2 : Demande d'envoi de l'e-mail
                if ($request->post('action_type') === 'request_reset') {
                    if ($request->post('email') !== null) {
                        $email = $request->post('email');
                    } else {
                        $email = '';
                    }

                    if (!empty($email)) {
                        $model->sendMail($email);
                        return Response::redirect('index.php?action=forgot_pwd&mail=sent');
                    } else {
                        $messageError = "Veuillez saisir votre adresse email.";
                    }
                }
            }
        }


    //Recup du token pour vue
        if ($request->get('token')) {
            $token = $request->get('token');
           //dans le cas ou le token est dans le post
        } elseif ($request->post('token') !== null) {
            $token = $request->post('token');
        }


        //Appel de vue
        $mailSent = $request->get('mail') === 'sent';
        $vue = (new ForgotPasswordView())->show($token, $messageError, $mailSent);
        return $this->view->render('Mot de passe oublié', $vue);
    }
}
