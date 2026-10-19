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

class ProfileController {
    public function __construct(private readonly View $view) {}

    public function execute(Request $request): Response {
        // On check si le role est enseignant
        if ($request->session('role') === 'enseignant') {
            // On affiche la page de l'enseignant
            $vue = (new \modules\views\TeacherProfileView())->show();
            return $this->view->render('teacher', $vue);
        }
        // Si c'est pas un prof alors on check si c'est un eleve
        else if ($request->session('role') === 'eleve') {
            // On affiche la page de l'eleve
            $vue = (new \modules\views\StudentProfileView())->show();
            return $this->view->render('student', $vue);
        }
        // Sinon si le role est anonyme on fait la vue anonyme
        else {
            // On affiche la page anonyme
            $vue = (new \modules\views\UnknownProfileView())->show();
            return $this->view->render('unknown', $vue);
        }
    }
}
