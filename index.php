<?php

require '_assets/includes/autoloader.php';
session_start();

try {
    $routes = [
        'home' => \modules\controllers\HomepageController::class,
        'login' => \modules\controllers\LoginController::class,
        'signup' => \modules\controllers\SignupController::class,
        'account' => \modules\controllers\AccountController::class,
        'book' => \modules\controllers\BookController::class,
        'world' => \modules\controllers\WorldController::class,
        'activity' => \modules\controllers\ActivityController::class,
        'forgot_pwd' => \modules\controllers\ForgotPasswordController::class,
        'delete' => \modules\controllers\DeleteController::class,
        'logout' => \modules\controllers\LogoutController::class,
    ];

    $action = filter_input(INPUT_GET, 'action') ?: 'home';
    if (!isset($routes[$action])) {
        throw new Exception('La page que vous recherchez n\'existe pas');
    }

    $controller = new $routes[$action]();
    $controller->execute();
} catch (Exception $e) {
    (new \modules\views\Error($e->getMessage()))->show();
}
?>