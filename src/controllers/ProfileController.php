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
            $userId = $request->session('userId');
            if ($userId === null) {
                return Response::redirect('index.php?action=login');
            }

            if ($request->method === 'POST' && $request->get('subaction') === 'generate_code') {
            // On genere le code class qui sera aleatoire et securise
                $nomClasse = $request->post('className');
                $cycle = $request->post('level');

                if ($nomClasse === '' || $cycle === '') {
                    $error = 'Veuillez indiquer un nom et un cycle de classe.';
                } else try {
                    $createdCode = $classModel->addClass($userId, $nomClasse, $cycle);
                } catch (\RuntimeException $e) {
                    $error = $e->getMessage();
                } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                    error_log('Création de classe impossible : ' . $e->getMessage());
                    $error = 'Error : Code class cannot be created';
                }
            }

            if ($request->method === 'POST' && $request->post('action_type') === 'create_student') {
                $pseudo = $request->post('pseudo');
                $password = $request->post('password');
                $codeClasse = $request->post('code');
                try {
                    if ($codeClasse && $pseudo && $password) {
                        $classModel->createStudent($userId, $pseudo, $password, $codeClasse);
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
                $classes = $classModel->getClassesByTeacher($userId);
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
                        $classModel->importStudentsCsv($userId, $codeClasse, $fichier['tmp_name']);
                        return Response::redirect('index.php?action=profile');
                    } catch (ExceptionDatabaseController | PDOException | Exception $e) {
                        $error = 'Erreur lors de l\'importation : ' . $e->getMessage();
                    }
                }
            }
        } else if ($request->session('role') === 'student') {
            $classModel = new ClassModel(DatabaseConnection::getInstance());
            $userId = $request->session('userId');
            if ($userId === null) {
                return Response::redirect('index.php?action=login');
            }

            try {
                $currentClass = $classModel->getClassInfo($userId);
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
