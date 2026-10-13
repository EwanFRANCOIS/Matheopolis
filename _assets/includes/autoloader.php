<?php

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function ($class): void {
            $fichier = __DIR__ . '/../../' . str_replace('\\', '/', $class) . '.php';
            if (is_file($fichier)) {
                require_once $fichier;
            }
        });
    }
}
    Autoloader::register();
