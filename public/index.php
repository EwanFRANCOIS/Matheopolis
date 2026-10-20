<?php

require_once __DIR__ . '/../src/core/autoloader.php';

use src\core\Request;
use src\core\Response;
use src\core\Router;
use src\core\View;


session_start();

try {

    // On fabrique la requete global
    $request = Request::fromGlobals();

    $routes = require __DIR__ . '/../config/routes.php';
    $view = new View($request->session('email') ?? $request->session('username'));
    $factories = [];
    foreach ($routes as $classe) {
        $factories[$classe] = fn() => new $classe($view);
    }
    $factories[\src\controllers\LogoutController::class] =
        fn() => new \src\controllers\LogoutController();

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