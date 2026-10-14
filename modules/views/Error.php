<?php

namespace modules\views;

use modules\views\components\Alert;
use modules\views\components\Link;
use modules\views\components\Paragraph;

class Error
{
    public function __construct(private string $message)
    {
    }

    public function show(): string
    {
        $alert = new Alert([$this->message]);
        $link = new Link('index.php', 'Retour à l\'accueil');

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
