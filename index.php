<?php

require_once '_assets/includes/autoloader.php';

use modules\core\Request;
use modules\core\Response;
use modules\core\Router;
use modules\core\View;


session_start();

try {

    // On fabrique la requete global
    $request = Request::fromGlobals();

    $routes = require __DIR__ . '/config/routes.php';
    $view = new View($request->session('email'));
    $factories = [];
    foreach ($routes as $classe) {
        $factories[$classe] = fn() => new $classe($view);
    }
    $factories[\modules\controllers\LogoutController::class] =
        fn() => new \modules\controllers\LogoutController();

    $router = new Router($routes, $factories);
    $response = $router->dispatch($request);
} catch (Exception $e) {
    $response = new Response(
        content: '<h1>Erreur</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>',
        statusCode: 404
    );
}

$response->send();
?>