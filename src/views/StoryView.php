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

        $firstChapter = isset($_GET['chapitre']) ? (int)$_GET['chapitre'] : 0;
        if (isset($_GET['dialogue'])) {
            $dialogueDeDepart = (int)$_GET['dialogue'];
        } elseif ($firstChapter > 0) {
            $dialogueDeDepart = -1;
        } else {
            $dialogueDeDepart = -2;
        }
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
        <div id="btn-dialogue-suivant">
            <?= (new Button('>>'))->render(); ?>
        </div>

        <div id="boite-enigme" class="card" style="display: none; margin-top: 20px;">
            <h3>Énigme</h3>
            <p id="quiz-question"></p> 
            
            <label for="reponse-joueur">Votre réponse :</label>
            <input type="text" id="reponse-joueur" style="padding: 5px; margin: 10px 0;">
            
            <button id="btn-valider-enigme" class="btn">Valider</button>
            <p id="message-erreur" style="color: red; font-weight: bold; margin-top: 10px;"></p>
        </div>

        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="activity">
            <input type="hidden" name="step" value="game">
            <?= (new Button('Activités'))->render(); ?>
            </form>

        <script>
            let reponseAttendueActuelle = "";
            let messageErreurActuel = "";
            let histoire = <?= $jsonContent ?>;
        
            let currentChapter = <?= $firstChapter ?>;
            let currentIndex = <?= $dialogueDeDepart ?>;

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

                    let nextButton = document.getElementById('btn-dialogue-suivant');
                    let boiteEnigme = document.getElementById('boite-enigme');

                    if (dialogueLine.action === "lancer_activite") {
                        nextButton.style.display = 'none';
                        boiteEnigme.style.display = 'block';

                        if (dialogueLine.quiz) {
                            document.getElementById('quiz-question').innerHTML = dialogueLine.quiz.question;
                            reponseAttendueActuelle = dialogueLine.quiz.reponse_attendue;
                            messageErreurActuel = dialogueLine.quiz.message_erreur || "Mauvaise réponse, essayez encore !";
                        }
                    } else {
                        nextButton.style.display = 'block';
                        boiteEnigme.style.display = 'none';
                    }
                }
            }

            afficherDialogue();

            document.getElementById('btn-dialogue-suivant').firstElementChild.addEventListener('click', function(e) {
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
                        
                        document.getElementById('btn-dialogue-suivant').style.display = 'none'; 
                    }
                }
            });

            document.getElementById('btn-valider-enigme').addEventListener('click', function() {
                let reponse = document.getElementById('reponse-joueur').value.trim();
                let messageErreur = document.getElementById('message-erreur');

                if (reponse.toLowerCase() === reponseAttendueActuelle.toLowerCase()) {
                    messageErreur.textContent = ""; 
                    document.getElementById('reponse-joueur').value = ""; 
                    
                    document.getElementById('boite-enigme').style.display = 'none'; 
                    document.getElementById('btn-dialogue-suivant').style.display = 'inline-block'; 
                    
                    document.getElementById('btn-dialogue-suivant').firstElementChild.click();
                } else {
                    messageErreur.textContent = messageErreurActuel;
                }
            });

        </script>
        <?php
        return ob_get_clean();
    }
}