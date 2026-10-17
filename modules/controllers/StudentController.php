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

class StudentController {
    public function __construct(private readonly View $view) {}

    public function execute(Request $request): Response {

        if ($request->session('role') !== 'eleve') {
            // Le role ne correspond pas au prof => renvoie une erreur
            return Response::redirect('index.php?error=unauthorized');
        } else {

            /*
            Tout les tests etc...
            */
            $vue = (new \modules\views\StudentPageView())->show();
            return $this->view->render('student', $vue); // Bon en soit faudrat voir le nom mais pour le moment dans ce truc de mrd je met Teacher
        }
    }
}

?>