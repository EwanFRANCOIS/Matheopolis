<?php

namespace src\controllers;

use src\core\DatabaseConnection;
use src\core\Response;
use src\core\Request;
use src\core\View;
use src\models\DeleteModel;
use PDOException;
use Exception;

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
        if ($request->session('userId') !== null) {
            $error = null;
            $id = $request->session('userId');
            if ($request->method === 'POST') {
                $_action = $request->post('action');

                if ($_action === 'deleteUser') {
                    $password = $request->post('password');
                    if ($password) {
                        try {
                            $deleteModel = new DeleteModel(DatabaseConnection::getInstance());
                            $delete = $deleteModel->getDelete((int) $id, $password);

                            if ($delete) {
                                $request->destroySession();
                                return Response::redirect('/index.php');
                            }
                            $error = 'Mot de passe incorrect';
                        } catch (PDOException | Exception $e) {
                            error_log('Suppression du compte impossible : ' . $e->getMessage());
                            $error = 'La suppression du compte est impossible pour le moment.';
                        }
                    } else {
                        $error = 'Veuillez remplir tous les champs';
                    }
                }
            }
            $vue = (new \src\views\DeleteView($error))->show();
            return $this->view->render('Suppression', $vue);
        } else {
            return Response::redirect("/index.php?action=account");
        }
    }
}
