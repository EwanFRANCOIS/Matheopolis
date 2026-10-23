<?php

namespace src\views;

use src\views\components\Link;
use src\views\components\Paragraph;

class BookView
{
    public function show(): string
    {
        $text = new Paragraph([
            'Si vous souhaitez acheter les livres pour suivre l\'histoire et l\'aventure de Laurence Guerney, vous pouvez acheter les livres sur le site officiel de Matheopolis en cliquant sur le lien ci-dessous.'
        ]);
        $link = new Link('https://www.matheopolis.org/les-livres', 'Lien vers les livres');

        ob_start();
        ?>

<header>
    <h1>Les livres</h1>
</header>

<div class="card">
    <?= $text ?>
    <br>
    <?= $link ?>
</div>

        <?php
        return ob_get_clean();
    }
}
