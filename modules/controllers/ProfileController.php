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
        if ($request->session('role') === 'enseignant') {

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
                return Response::redirect('index.php?action=profile');
            } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                $error = 'Error : Code class cannot be created';
            }
        }

        if ($request->method === 'POST' && $request->post('action_type') === 'create_student') {
                $pseudo = $request->post('pseudo');
                $password = $request->post('password');
                $codeClasse = $request->session('codeClasse');

                try {
                    if ($codeClasse && $pseudo && $password) {
                        $classModel->createStudent($teacherId, $pseudo, $password, $codeClasse);
                        return Response::redirect('index.php?action=profile');
                    } else {
                        $error = 'Veuillez remplir tous les champs et générer un code de classe.';
                    }
                } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                    $error = 'Erreur : Ce pseudo existe déjà ou le compte ne peut pas être créé.';
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
        }
        $vue = (new \modules\views\ProfileView())->show(
            $request->session('role'), 
            $error, 
            $currentCode, 
            $students
        );
        return $this->view->render('profile', $vue);
    }
}
