<?php

namespace src\controllers;

use src\core\DatabaseConnection;
use src\core\Response;
use src\core\Request;
use src\core\View;
use src\models\DeleteModel;

/**
 * Contrôleur de la page de suppression.
 *
 * Gère le traitement du formulaire de suppression en POST
 * et la deconnexion de l'utilisateur.
 */
class DeleteController
{
    public function __construct(private readonly View $view)
    {
    }

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
            $_login = $request->session('email');
            if ($request->method === 'POST') {
                $_action = $request->post('action');

                if ($_action === 'deleteUser') {
                    $Password = $request->post('password');
                    if ($Password) {
                        $deleteModel = new DeleteModel(DatabaseConnection::getInstance());
                        $delete = $deleteModel->getDelete($_login, $Password);

                        if ($delete) {
                            $request->destroySession();
                            return Response::redirect('index.php');
                        } else {
                            $error = 'Mot de passe incorrect';
                        }
                    } else {
                        $error = 'Veuillez remplir tous les champs';
                    }
                }
            }
            $vue = (new \src\views\DeleteView())->show($error);
            return $this->view->render('Suppression', $vue);
        } else {
            return Response::redirect("index.php?action=account");
        }
    }
}
