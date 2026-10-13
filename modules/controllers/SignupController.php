<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\core\Response;
use modules\core\Request;
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
    public function execute(Request $request): Response
    {
        $error = null;
        if (($request->post('action')) && !empty($request->post('action'))) {
            if ($request->method === 'POST') {
                $_login = $request->post('email');
                $Password = $request->post('password');
                $_verifPassword = $request->post('verif');
                $_action = $request->post('action');

                if (empty($_login) || empty($Password) || empty($_verifPassword)) {
                    $error = 'Veuillez remplir le formulaire';
                } elseif ($_verifPassword !== $Password) {
                    $error = 'Les mots de passe ne correspondent pas';
                } elseif ($_action === 'inscription') {
                    try {
                        $signupModel = new SignupModel(DatabaseConnection::getInstance());
                        $signup = $signupModel->getSignup($_login, $Password);
                        Request::setSession('email', $_login);
                        return Response::redirect('index.php?success=true');

                        # Les | permettent juste de reunnir 3 catch en 1 seul pour eviter d'en avoir 3
                    } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                        $error = 'Ce compte existe déjà';
                    }
                } else {
                    $error = 'Veuillez remplir le formulaire';
                }
            }
        }

        $vue = (new \modules\views\SignupView())->show($error);
        return new Response($vue);
    }
}
