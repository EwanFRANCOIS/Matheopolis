<?php

namespace modules\views;

class BookView
{
    public function show(): string
    {
        ob_start();
        ?>

<header>
    <h1>Les livres</h1>
</header>

<div class="card">
    <p>
        Si vous souhaitez acheter les livres pour suivre l'histoire et l'aventure de 
        Laurence Guerney, vous pouvez acheter les livres.
        sur le site officiel de Matheopolis en cliquant sur le lien ci-dessous.
    </p>

    <br>

    <a href="https://www.matheopolis.org/les-livres">Lien vers les livres</a>
</div>

        <?php
        return ob_get_clean();
    }
}

?>