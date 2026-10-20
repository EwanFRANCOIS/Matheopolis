<?php

namespace src\core;

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
                    $_SERVER['REQUEST_METHOD'] ?? 'GET',
                    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
                    $_GET,
                    $_POST,
        );
    }

    public function get(string $key, $default = null) {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, $default = null) {
        return $this->post[$key] ?? $default;
    }

    public function session(string $key, mixed $default = null): mixed {
        return $_SESSION[$key] ?? $default;
    }

    public function setSession(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public function destroySession(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}

?>