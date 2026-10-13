<?php

namespace _assets\http;

// le type readonly est juste une securite pour pas que les variables superglobales ou importante
// soient modifiees
class Request {
    public function __construct(
        public readonly string $method,     // stock la methode ($_GET ou $_POST ou $_SESSION)
        public readonly string $path,       // stock le chemin de la page recherchee
        public readonly array $get,         // stock le tableau du $_GET (les parametres dans l'URL)
        public readonly array $post,        // pareil mais pour le $_POST (donnée d'un formulaire)
    ) {}

    public static function fromGlobals(): self {
        return new self(
          $_SERVER['REQUEST_METHOD'],   // recup $_GET et $_POST
          parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),    // nettoie l'URL pour garder seulement le chemin
          $_GET,    // recup les param du $_GET
          $_POST,   // pareil mais pour $_POST
        );
    }
}

?>