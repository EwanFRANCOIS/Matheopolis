<?php

require_once '_assets/includes/autoloader.php';

use modules\core\Request;
use modules\core\Response;

session_start();

try {

    // On fabrique la requete global
    $request = Request::fromGlobals();

    // on recup l'action via la method get() dans l'url si ya pas d'action, on envois vers le homepage
    $action = $request->get('action', 'home');

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

    if (!isset($routes[$action])) {
        throw new Exception('La page que vous recherchez n\'existe pas');
    }

    $controller = new $routes[$action]();
    $response = $controller->execute($request);
} catch (Exception $e) {
    $reponse = new Response(
        content: '<h1>Erreur</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>',
        statusCode: 404
    );
}

$reponse->send();
?>