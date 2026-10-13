<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\core\Request;
use modules\core\Response;
use modules\models\LoginModel;

/**
 * Contrôleur de la page de connexion.
 *
 * Gère le traitement du formulaire de connexion en POST
 * et la mise en session de l'utilisateur authentifié.
 */

class LoginController
{
/**
     * Exécute la logique de connexion.
     *
     * Traite les données POST transmises, tente l'authentification
     * via le modèle, initialise la session et redirige vers l'accueil ou affiche les erreurs.
     *
     * @return void
     */
    public function execute(Request $request): Response
    {
        $error = null; // On met l'erreur a null de base car on la met dans le show a la fin

        if ($request->method === 'POST') {
            $_action = $request->post('action');

            if ($_action === 'logUser') {
                $_login = $request->post('email');
                $_password = $request->post('password');

                if ($_login && $_password) {
                    $connectionModel = new LoginModel(DatabaseConnection::getInstance());
                    $connection = $connectionModel->getConnection($_login, $_password);

                    if (!empty($connection)) {
                        Request::setSession('email', $_login);
                        return Response::redirect('index.php');
                    } else {
                        $error = 'Email ou mot de passe incorrect';
                    }
                } else {
                    $error = 'Veuillez remplir tous les champs';
                }
            }
        }

        $vue = (new \modules\views\LoginView())->show($error);
        return new Response($vue);
    }
}
