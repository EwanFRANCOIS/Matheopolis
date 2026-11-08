<?php

namespace src\views;

use src\views\components\Typewriter;
use src\views\components\Button;


class StoryView
{
    public function show(): string
    {
        $jsonPath = __DIR__ . '/../../public/story.json';
        $jsonContent = file_get_contents($jsonPath);
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
        <div class="card">
            <div class="dialog_wrapper">
                <img id="story-image" class="dialog_profile">
                <h3 id="story-nom"></h3>
                <div class="dialog_content">
                    <span id="story-texte"></span>
                </div>
            </div>
        </div>
        <button id="btn-dialogue-suivant">Suivant</button>
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="activity">
            <input type="hidden" name="step" value="game">
            <?= (new Button('Activités'))->render(); ?>
            </form>

        <script>
            let histoire = <?= $jsonContent ?>;
            let indexCourant = 0;

            function afficherDialogue() {
            let replique = histoire.chapitres[0].dialogues[indexCourant];
            
            let perso = histoire.personnages[replique.perso];

            document.getElementById('');

            document.getElementById('story-image').src = perso.avatar;
            document.getElementById('story-nom').textContent = perso.nom;
            document.getElementById('story-texte').textContent = replique.texte;

            }

            afficherDialogue();

        </script>
        <?php
        return ob_get_clean();
    }
}