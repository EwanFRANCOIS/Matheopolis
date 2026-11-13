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
        <div id="btn-next-dialogue">
            <?= (new Button('>>'))->render(); ?>
        </div>

        <div id="quiz-box" class="card parchment form-area" style="display: none; margin-top: 20px;">
            <h2>Énigme</h2>
            
            <p id="quiz-question" class="small"></p> 
            
            <label for="player-answer">Votre réponse :</label>
            <input type="text" id="player-answer" autocomplete="off" style="max-width: 300px; text-align: center;">
            
            <button id="btn-submit-quiz" class="btn red">Valider</button>
            
            <div id="error-message" class="alert" style="display: none; width: 100%; text-align: center;"></div>
        </div>

        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="activity">
            <input type="hidden" name="step" value="game">
            <?= (new Button('Activités'))->render(); ?>
            </form>

        <script>
            let currentExpectedAnswer = "";
            let currentErrorMessage = "";
            let histoire = <?= $jsonContent ?>;
        
            let currentChapter = <?= $firstChapter ?>;
            let currentIndex = <?= $firstDialogue ?>;

            function afficherDialogue() {
                
                let chapitre = histoire.chapitres[currentChapter]; 

                if (currentIndex === -2) {
                    document.getElementById('story-image').src = "/Images/verity.png";
                    document.getElementById('story-nom').textContent = "Système";
                    document.getElementById('story-texte').textContent = "Pensez à appuyer sur le bouton en dessous lorsque vous avez terminé de lire un dialogue !";
                } 
                else if (currentIndex === -1) {
                    document.getElementById('story-image').src = "/Images/verity.png";
                    
                    document.getElementById('story-nom').textContent = chapitre.titre; 
                    document.getElementById('story-texte').textContent = "Prêt à commencer la lecture ?";
                }
                else {
                    let dialogueLine = chapitre.dialogues[currentIndex];
                    let perso = histoire.personnages[dialogueLine.perso];

                    document.getElementById('story-image').src = perso.avatar;
                    document.getElementById('story-nom').textContent = perso.nom;
                    document.getElementById('story-texte').textContent = dialogueLine.texte;

                    let nextButton = document.getElementById('btn-next-dialogue');
                    let boiteEnigme = document.getElementById('quiz-box');

                    if (dialogueLine.action === "lancer_activite") {
                        nextButton.style.display = 'none';
                        boiteEnigme.style.display = 'block';

                        if (dialogueLine.quiz) {
                            document.getElementById('quiz-question').innerHTML = dialogueLine.quiz.question;
                            currentExpectedAnswer = dialogueLine.quiz.reponse_attendue;
                            currentErrorMessage = dialogueLine.quiz.message_erreur || "Mauvaise réponse, essayez encore !";
                        }
                    } else {
                        nextButton.style.display = 'block';
                        boiteEnigme.style.display = 'none';
                    }
                }
            }

            afficherDialogue();

            document.getElementById('btn-next-dialogue').firstElementChild.addEventListener('click', function(e) {
                e.preventDefault();
                
                currentIndex++;
                
                let totalDialogues = histoire.chapitres[currentChapter].dialogues.length;

                if (currentIndex < totalDialogues) {
                    afficherDialogue();
                } 
                else {
                    if (currentChapter < histoire.chapitres.length - 1) {
                        currentChapter++;
                        currentIndex = -1;
                        
                        afficherDialogue();
                    } 
                    else {
                        document.getElementById('story-image').src = "/Images/verity.png";
                        document.getElementById('story-nom').textContent = "Fin";
                        document.getElementById('story-texte').textContent = "L'histoire est terminée. Il est temps de passer à l'activité !";
                        
                        document.getElementById('btn-next-dialogue').style.display = 'none'; 
                    }
                }
            });

            document.getElementById('btn-submit-quiz').addEventListener('click', function() {
                let answer = document.getElementById('player-answer').value.trim();
                let errorMessageDisplay = document.getElementById('error-message');

                if (answer.toLowerCase() === currentExpectedAnswer.toLowerCase()) {
                    errorMessageDisplay.style.display = 'none';
                    document.getElementById('player-answer').value = ""; 
                    
                    document.getElementById('quiz-box').style.display = 'none'; 
                    document.getElementById('btn-next-dialogue').style.display = 'inline-block'; 
                    
                    document.getElementById('btn-next-dialogue').firstElementChild.click();
                } else {
                    errorMessageDisplay.textContent = currentErrorMessage;
                    errorMessageDisplay.style.display = 'block';
                }
            });

        </script>
        <?php
        return ob_get_clean();
    }
}