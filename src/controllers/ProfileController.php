<?php

namespace src\controllers;

use src\core\DatabaseConnection;
use src\core\Response;
use src\core\Request;
use src\core\View;
use src\models\ClassModel;
use src\models\SignupModel;
use src\core\ExceptionDatabaseController;
use src\views\TeacherDashboardView;
use PDOException;
use Exception;

class ProfileController {
    public function __construct(private readonly View $view) {}

    public function execute(Request $request): Response {
        $error = null;
        $currentCode = null;
        $students = [];
        if ($request->session('role') === 'teacher') {

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

        if ($request->method === 'POST' && $request->post('action_type') === 'import_csv') {
                $codeClasse = $request->session('codeClasse');
                $fichier = $_FILES['fichier_eleves'] ?? null;

                if (!$codeClasse) {
                    $error = 'Veuillez remplir tous les champs et générer un code de classe.';
                } elseif (!$fichier || $fichier['error'] !== UPLOAD_ERR_OK) {
                    $error = 'Veuillez sélectionner un fichier CSV valide.';
                } else {
                    try {
                        $classModel->importStudentsCsv($teacherId, $codeClasse, $fichier['tmp_name']);
                        return Response::redirect('index.php?action=profile');
                    } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                        $error = 'Erreur lors de l\'importation : ' . $e->getMessage();
                    }
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
