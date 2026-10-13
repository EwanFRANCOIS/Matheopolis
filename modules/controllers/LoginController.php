<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
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
    public function execute(): void
    {
        $error = null; // On met l'erreur a null de base car on la met dans le show a la fin

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_action = filter_input(INPUT_POST, 'action');

            if ($_action === 'logUser') {
                $_login = filter_input(INPUT_POST, 'email');
                $Password = filter_input(INPUT_POST, 'password');

                if ($_login && $Password) {
                    $connectionModel = new LoginModel(DatabaseConnection::getInstance());
                    $connection = $connectionModel->getConnection($_login, $Password);

                    if (!empty($connection)) {
                        $_SESSION['email'] = $_login;
                        header('Location: index.php');
                        exit();
                    } else {
                        $error = 'Email ou mot de passe incorrect';
                    }
                } else {
                    $error = 'Veuillez remplir tous les champs';
                }
            }
        }

        (new \modules\views\LoginView())->show($error);
    }
}
