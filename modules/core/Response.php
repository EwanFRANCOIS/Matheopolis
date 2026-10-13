<?php

namespace modules\core;

class Response {

    // constructeur par defaut
    public function __construct(
        private string $content = '',   // Le code HTML a affiché (ici vide par defaut du coup)
        private int $statusCode = 200,  // 200 => OK 404 => Erreur page pas trouvé
        private ?string $url = null    // Url de redirection eventuel
    ) {}

    // Redirection vers la page souhaitais qu'on va juste mettre en param lors de l'appel
    public static function redirect(string $url): self {
        return new self(
            content: '',
            statusCode: 302,
            url: $url
        );
    }

    public function send(): void {
        if ($this->url) {
            header('Location: ' . $this->url);
            exit();
        }

        http_response_code($this->statusCode);
        echo $this->content;
    }
}

?>