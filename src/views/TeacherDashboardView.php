<?php

namespace src\views;
use src\views\components\Alert;
use src\views\components\Button;
use src\views\components\Form;

/**
 * Vue pour affichage du tableau de bord enseignant (Gestion des classes F2).
 */

class TeacherDashboardView
{
    /**
     * Affiche le code d'invitation de la classe et la liste des élèves inscrits.
     *
     * @param string|null $codeClasse Le code d'invitation actuel de l'enseignant
     * @param array $students Tableau associatif contenant la liste des élèves inscrits
     * @return string Le code HTML généré
     */
    public function show(?string $codeClasse = null, array $students = [], ?string $error = null): string
    {
        // Création de message d'erreur en cas de probleme
        $messages = [];
        if ($error) {
            $messages[] = $error;
        }
       // Création d'alerte pour afficher code d'invitation (ou un message si pas la)
        $codeMessage = $codeClasse 
            ? "Votre code d'invitation de classe à partager à vos élèves : " . $codeClasse 
            : "Vous n'avez pas encore généré de code pour votre classe.";

        $alertCode = new Alert([$codeMessage]);
        $alertError = new Alert($messages);

        // Formulaire qui génère (ou regénère) un code de classe
        $buttonLabel = $codeClasse 
        ? 'Générer un nouveau code' 
        : 'Créer ma classe (Générer un code)';

        $form = new Form('/index.php?action=profile&subaction=generate_code', [
            new Button($buttonLabel, 'submit')
        ]);
        ob_start();
        ?>

    <header>
        <h1>Tableau de bord : Gestion des Classes</h1>
    </header>
    <?= $alertError ?>

    <div class="card">
            <?= $alertCode ?>
    </div>

    <div class="form-area" style="margin-top: 20px;">
        <?= $form ?>
    </div>

    <!-- création de compte pour élève-->
    <?php if ($codeClasse) : ?>
        <div class="form-area" style="margin-top: 30px; background: #f9f9f9; padding: 15px; border-radius: 5px;">
            <h3>Créer un compte pour un élève</h3>
            <form action="/index.php?action=profile" method="post">
                <input type="hidden" name="action_type" value="create_student">
                
                <div style="margin-bottom: 10px;">
                    <label for="pseudo">Pseudo de l'élève :</label><br>
                    <input type="text" id="pseudo" name="pseudo" required style="padding: 5px; width: 100%;">
                </div>

                <div style="margin-bottom: 10px;">
                    <label for="password">Mot de passe temporaire :</label><br>
                    <input type="password" id="password" name="password" required style="padding: 5px; width: 100%;">
                </div>

                <button type="submit" class="btn">Créer l'élève</button>
            </form>
        </div>
        <?php endif; ?>

    <div class="students-section" style="margin-top: 30px;">
        <h3>Liste de vos élèves inscrits :</h3>

        <?php if (empty($students)) : ?>
            <p><em>Aucun élève n'a rejoint votre classe pour l'instant.</em></p>
        <?php else : ?>
            <ul style="list-style-type: disc; padding-left: 20px;">
                <?php foreach ($students as $student) : ?>
                    <li>Élève : 
                        <!-- ENT_QUOTES permet de convertir les guillemets en chaine de caractere (htmlspecialchars ne le fait pas par défaut apparemment) -->
                        <strong><?= htmlspecialchars($student['pseudo'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
   </div>

<?php
        return ob_get_clean();
    }
}
    
