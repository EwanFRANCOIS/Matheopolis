<?php

namespace modules\views;

class StoryView
{
    public function show(): string
    {
        ob_start();
        ?>
        <header>
            <h1>Bienvenue, plongez au coeur de l'histoire de Matheopolis !</h1>
        </header>

        <div class="card">
            <!-- balise vide pour les caractères -->
            <p id="story-text"></p>
            <br>
            <button type="button" id="next-story-btn" class="btn">Continuer</button>
        </div>
        <script src="_assets/includes/scripts/story.js"></script>
        <?php
        return ob_get_clean();
        
    }
}

