<?php

class Autoloader
{
    public static function register(): void
    {
        $racine = dirname(__DIR__);

        spl_autoload_register(function (string $class) use ($racine): void {
            $prefixeCore = 'src\\core\\';
            if (str_starts_with($class, $prefixeCore)) {
                $classe = 'core/' . substr($class, strlen($prefixeCore));
            } else {
                $classe = str_replace('\\', '/', $class);
            }

            $fichier = $racine . '/' . $classe . '.php';
            if (is_file($fichier)) {
                require_once $fichier;
            }
        });
    }
}
    Autoloader::register();
