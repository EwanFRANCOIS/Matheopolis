<?php

namespace src\core;

use Exception;

final class Router
{
    public function __construct(
        private readonly array $routes,
        private readonly array $factories
    )
    {
    }

    public function dispatch(Request $request): Response
    {
        $action = $request->get('action', 'home');
        if (!is_string($action) || !isset($this->routes[$action])) {
            throw new Exception('La page que vous recherchez n\'existe pas');
        }

        $classe = $this->routes[$action];
        $controller = ($this->factories[$classe] ?? fn() => new $classe)();
        return $controller->execute($request);
    }
}
