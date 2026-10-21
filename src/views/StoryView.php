<?php

namespace src\views;

use src\views\components\Typewriter;
use src\views\components\Button;


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
            <div class="dialog_wrapper">
                <img src="/Images/verity.png" alt="image de test" class="dialog_profile">
                
                <div class="dialog_content">
                    <?= (new Typewriter("Dans cet univers, à la fois imaginaire et réel, Laurence côtoie l’esprit des savants de tous les temps et les notions mathématiques qui prennent corps.", 30))->render(); ?>
                    <br>
                    <?= (new Typewriter("Pensez à appuyer sur le bouton [>>] lorsque vous avez terminé de lire un dialogue !", 10))->render(); ?>
                </div>
            </div>
        </div>
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="activity">
            <input type="hidden" name="step" value="game">
            <?= (new Button('>>'))->render(); ?>
            </form>
        <?php
        return ob_get_clean();
    }
}