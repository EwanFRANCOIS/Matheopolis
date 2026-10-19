<?php

namespace modules\controllers;

use _assets\includes\DatabaseConnection;
use modules\core\Response;
use modules\core\Request;
use modules\core\View;
use modules\models\ClassModel;
use modules\models\SignupModel;
use includes\ExceptionDatabaseController;
use modules\views\TeacherDashboardView;
use PDOException;
use Exception;

class ProfileController {
    public function __construct(private readonly View $view) {}

    public function execute(Request $request): Response {
        $userRole = $request->session('role');
        $error = null;
        // On check si le role est enseignant
        if ($userRole === 'enseignant') {

            $classModel = new ClassModel(DatabaseConnection::getInstance());
            $teacherId = (int)$request->session('teacherId');

            if ($request->method === 'POST' && $request->get('subaction') === 'generate_code') {
            // On genere le code class qui sera aleatoire et securise
                $character = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $characterLen = strlen($character);
                $codeClasse = '';

            for ($i = 0; $i < 9; $i++) {
                $codeClasse .= $character[rand(0, $characterLen - 1)];
            }

            try {
                $classModel->updateTeacherClassCode($teacherId, $codeClasse);
                $request->setSession('codeClasse', $codeClasse);
                return Response::redirect('index.php?page=TeacherDashboard');
            } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                $error = 'Error : Code class cannot be created';
            }
        }

            // Récupération du code actuel et des élèves pour affichage normal de la page
            $currentCode = $request->session('codeClasse');
            $students = []; //Tableau de tableau contenant les élèves (+ leurs infos) associés a leurs professeurs
            try {
                $students = $classModel->getStudentsByTeacher($teacherId);
            } catch (Exception $e) {
                $error = 'Impossible de charger les élèves.';
            }

            // On affiche la page de l'enseignant
            $vue = (new \modules\views\TeacherDashboardView())->show($currentCode, $students, $error);
            return $this->view->render('Tableau de bord - Enseignant', $vue);

    }
        // Si c'est pas un prof alors on check si c'est un eleve
        else if ($userRole === 'eleve') {
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
