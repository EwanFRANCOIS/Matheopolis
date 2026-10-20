<?php

namespace modules\views;

use modules\views\components\Typewriter;
use modules\views\components\Button;


class StoryView
{
    public function show(): string
    {
        ob_start();
        ?>
        <header>
            <h1>Bienvenue, plongez au coeur de Matheopolis !</h1>
        </header>

        <div class="card">
            <!-- balise de test pour l'animation -->
            <?= (new Typewriter("Bienvenue sur Matheopolis !", 40))->render(); ?>
            <br>
            <!-- autre balise de test pour l'animation -->
            <?= (new Typewriter("Je m'affiche après la fin de l'animation !", 40))->render(); ?>
            <br>

            <form method="GET" action="index.php">
                <input type="hidden" name="action" value="activity">
                <input type="hidden" name="step" value="game">
                <?= (new \modules\views\components\Button('Continuer'))->render(); ?>
            </form>

        </div>
        <?php
        return ob_get_clean();
    }
}