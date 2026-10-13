<?php

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function ($class): void {
            $fichier = str_replace('\\', '/', $class) . '.php';
            if (file_exists($fichier)) {
                require_once $fichier;
            }
        });
    }
}
    Autoloader::register();
