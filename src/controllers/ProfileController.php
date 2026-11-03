<?php

namespace src\controllers;

use src\core\DatabaseConnection;
use src\core\Response;
use src\core\Request;
use src\core\View;
use src\models\ClassModel;
use src\core\ExceptionDatabaseController;
use PDOException;
use Exception;

class ProfileController {
    public function __construct(private readonly View $view) {}

    public function execute(Request $request): Response {
        $error = null;
        $currentClass = null;
        $createdCode = null;
        $classes = [];
        $students = [];
        if ($request->session('role') === 'teacher') {

            $classModel = new ClassModel(DatabaseConnection::getInstance());
            $teacherId = (int)$request->session('teacherId');

            if ($request->method === 'POST' && $request->get('subaction') === 'generate_code') {
            // On genere le code class qui sera aleatoire et securise
                $nomClasse = trim((string)$request->post('className'));
                $cycle = trim((string)$request->post('level'));

                if ($nomClasse === '' || $cycle === '') {
                    $error = 'Veuillez indiquer un nom et un cycle de classe.';
                } else try {
                    $createdCode = $classModel->addClass($teacherId, $nomClasse, $cycle);
                    return Response::redirect('index.php?action=profile');
                } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                    $error = 'Error : Code class cannot be created';
                }
            }

            if ($request->method === 'POST' && $request->post('action_type') === 'create_student') {
                $pseudo = $request->post('pseudo');
                $password = $request->post('password');
                $codeClasse = $request->post('code');
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

            try {
                // Récupération du code actuel et des élèves pour affichage normal de la page
                $classes = $classModel->getClassesByTeacher($teacherId);
            } catch (Exception $e) {
                $error = 'Impossible de charger les informations de la classe.';
            }

            if ($request->method === 'POST' && $request->post('action_type') === 'import_csv') {
                $codeClasse = $request->post('code');
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
        } else if ($request->session('role') === 'student') {
            $classModel = new ClassModel(DatabaseConnection::getInstance());
            $studentId = (int)$request->session('studentId');

            try {
                $currentClass = $classModel->getClassInfo($studentId);
            } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                $error = 'Impossible de récupérer le nom de la classe.';
            }
        }

        $vue = (new \src\views\ProfileView(
            $request->session('role'), 
            $error, 
            $currentClass,
            $students,
            $classes,
            $createdCode
        ))->show();
        return $this->view->render('profile', $vue);
    }
}
