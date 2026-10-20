<?php

namespace src\controllers;

use src\core\DatabaseConnection;
use src\core\Request;
use src\core\Response;
use src\core\View;
use src\models\LoginModel;

/**
 * Contrôleur de la page de connexion.
 *
 * Gère le traitement du formulaire de connexion en POST
 * et la mise en session de l'utilisateur authentifié.
 */

class LoginController
{
    public function __construct(private readonly View $view)
    {
    }

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
                $_login = trim((string)$request->post('login'));
                $_password = $request->post('password');

                if (empty($_login) || empty($_password)) {
                    $error = 'Veuillez remplir tout les champs';
                } else {
                    $isEmail = filter_var($_login, FILTER_VALIDATE_EMAIL) !== false;
                    $connectionModel = new LoginModel(DatabaseConnection::getInstance());
                    $connection = $connectionModel->getConnection($_login, $_password, $isEmail);

                    if ($connection !== null) {
                        session_regenerate_id(true);
                        $request->setSession('email', $connection->email);
                        $request->setSession('username', $connection->username);
                        $request->setSession('role', $connection->role);
                        return Response::redirect('index.php');
                    } else {
                        $error = 'Email, pseudo ou mot de passe incorrect';
                    }
                }
            }
        }
        $vue = (new \src\views\LoginView())->show($error);
        return $this->view->render('Connexion', $vue);
    }
}
