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
        $vue = (new \modules\views\ProfileView())->show($request->session('role'));
            return $this->view->render('teacher', $vue);
    }
}
