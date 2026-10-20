<?php

namespace src\views;

use src\views\components\Alert;
use src\views\components\Link;
use src\views\components\Paragraph;

class Error
{
    public function __construct(private string $message)
    {
    }

    public function show(): string
    {
        $alert = new Alert([$this->message]);
        $link = new Link('/index.php', 'Retour à l\'accueil');

        ob_start();
        ?>
        <header><h1>Oops</h1></header>
        <div class="card">
            <?= $alert ?>
            <?= $link ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
