<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\core\Response;
use modules\core\Request;
use modules\core\View;
use modules\models\SignupModel;
use includes\ExceptionDatabaseController;
use PDOException;
use Exception;

class TeacherController {
    public function __construct(private readonly View $view) {}

    public function execute(Request $request): Response {

        if ($request->session('role') !== 'enseignant') {
            // Le role ne correspond pas au prof => renvoie une erreur
            return Response::redirect('index.php?error=unauthorized');
        } else {

            /*
            CREATION DU COMPTE DE L'ELEVE AVEC UN FORMULAIRE
            On recup les donnees mise dans le formulaire creee dans la vue TeacherView (ou un truc du genre)
            et apres on fait la creation du compte dans la table
            */
            $vue = (new \modules\views\TeacherProfileView())->show();
            return $this->view->render('teacher', $vue); // Bon en soit faudrat voir le nom mais pour le moment dans ce truc de mrd je met Teacher
        }
    }
}

?>