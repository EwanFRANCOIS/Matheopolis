<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\core\Response;
use modules\core\Request;
use modules\models\DeleteModel;

/**
 * Contrôleur de la page de suppression.
 *
 * Gère le traitement du formulaire de suppression en POST
 * et la deconnexion de l'utilisateur.
 */
class DeleteController
{
    /**
     * Exécute la logique de suppression.
     *
     * Traite les données POST transmises, tente la suppression
     * via le modèle, reinitialise la session et redirige vers l'accueil ou affiche les erreurs.
     *
     * @return void
     */
    public function execute(Request $request): Response
    {
        if ($request->session('email') !== null) {
            $error = null;
            $_login = $request->Session('email');
            if ($request->method === 'POST') {
                $_action = $request->post('action');

                if ($_action === 'deleteUser') {
                    $Password = $request->post('password');
                    if ($Password) {
                        $deleteModel = new DeleteModel(DatabaseConnection::getInstance());
                        $delete = $deleteModel->getDelete($_login, $Password);

                        if ($delete) {
                            Request::destroySession();
                            return Response::redirect('index.php');
                        } else {
                            $error = 'Mot de passe incorrect';
                        }
                    } else {
                        $error = 'Veuillez remplir tous les champs';
                    }
                }
            }
            $vue = (new \modules\views\DeleteView())->show($error);
            return new Response($vue);
        } else {
            return Response::redirect("index.php?action=account");
        }
    }
}
