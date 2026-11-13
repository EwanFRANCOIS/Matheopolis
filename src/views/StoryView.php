<?php

namespace src\views;

use src\views\components\Typewriter;
use src\views\components\Button;
use src\views\components\Input;
use src\views\components\Form;
use src\views\components\Paragraph;


class StoryView
{
    public function show(int $firstChapter = 0, int $firstDialogue = -2): string
    {
        $jsonPath = __DIR__ . '/../../public/story.json';
        $jsonContent = file_get_contents($jsonPath);

        $btnSubmitQuiz = new Button(label: 'Valider', type: 'button', class: 'small', id: 'btn-submit-quiz');

        $answerQuiz = new Input(name: 'answerQuiz', type: 'text', placeholder: 'Votre réponse...', id: 'player-answer', style: 'max-width: 300px; text-align: center;', required: false);

        $formStory = new Form('index.php', [
            new Input(type: 'hidden', name: 'action', value: 'activity'),
            new Input(type: 'hidden', name: 'step', value: 'game'),
            new Button('Activités')
        ]);

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
            
            <?=$answerQuiz->render()?>

            <?=$btnSubmitQuiz->render()?>
            
            <div id="error-message" class="alert" style="display: none; width: 100%; text-align: center;"></div>
        </div>

        <?=$formStory?>

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

            // 1. Écouteur pour le bouton suivant [>>]
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

                document.getElementById('btn-submit-quiz').addEventListener('click', function(e) {
                e.preventDefault();
                
                try {
                    console.log("1. Clic capturé");

                    let answerInput = document.getElementById('player-answer');
                    console.log("2. Champ input trouvé :", answerInput);

                    let errorMessageDisplay = document.getElementById('error-message');
                    
                    if (!answerInput) {
                        console.error("ERREUR : Impossible de trouver l'élément #player-answer !");
                        return;
                    }

                    let answer = answerInput.value.trim();
                    let userAns = String(answer).toLowerCase();
                    let expectedAns = String(currentExpectedAnswer).toLowerCase();

                    console.log("3. Valeur tapée :", JSON.stringify(userAns), "/ Attendue :", JSON.stringify(expectedAns));

                    if (userAns === expectedAns) {
                        console.log("4. Succès !");
                        errorMessageDisplay.style.display = 'none';
                        answerInput.value = ""; 
                        
                        document.getElementById('quiz-box').style.display = 'none'; 
                        document.getElementById('btn-next-dialogue').style.display = 'inline-block'; 
                        
                        let nextBtn = document.getElementById('btn-next-dialogue').firstElementChild;
                        if (nextBtn) nextBtn.click();
                    } else {
                        console.log("5. Échec de la réponse.");
                        errorMessageDisplay.textContent = currentErrorMessage;
                        errorMessageDisplay.style.display = 'block';
                    }
                } catch (err) {
                    console.error("ERREUR JS CRITIQUE :", err);
                }
            });

        </script>
        <?php
        return ob_get_clean();
    }
}