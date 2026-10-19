<?php

namespace modules\views;

use modules\views\components\Typewriter;

class StoryView
{
    public function show(): string
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ob_start();
        ?>
        <header>
            <h1>Bienvenue, plongez au coeur de Matheopolis !</h1>
        </header>

        <div class="card">
            <!-- balise de test pour l'animation -->
            <?= (new Typewriter("Bienvenue sur Matheopolis !", 40))->render(); ?>
            <br>
            <button type="button" id="next-story-btn" class="btn">Continuer</button>
        </div>
        <script src="_assets/includes/scripts/story.js"></script>
        <?php
        return ob_get_clean();
    }
}