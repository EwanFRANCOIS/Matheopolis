<?php

namespace src\core;

class Response {

    // constructeur par defaut
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = []
    ) {}

    // Redirection vers la page souhaitais qu'on va juste mettre en param lors de l'appel
    public static function redirect(string $url): self {
        return new self(
            content: '',
            statusCode: 302,
            headers: ['Location' => $url]
        );
    }

    public function send(): void {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;

        if (isset($this->headers['Location'])) {
            exit();
        }
    }
}

?>