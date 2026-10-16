<?php

namespace modules\views;
use modules\views\components\Alert;
use modules\views\components\Button;
use modules\views\components\Form;

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
    public function show(?string $codeClasse = null, array $students = []): string
    {
       // Création d'alerte pour afficher code d'invitation (ou un message si pas la)
        $codeMessage = $codeClasse 
            ? "Votre code d'invitation de classe à partager à vos élèves : " . $codeClasse 
            : "Vous n'avez pas encore généré de code pour votre classe.";

        $alertCode = new Alert([$codeMessage]);

        // Formulaire qui génère (ou regénère) un code de classe
        $buttonLabel = $codeClasse 
        ? 'Générer un nouveau code' 
        : 'Créer ma classe (Générer un code)';

        $form = new Form('index.php?action=teacher&subaction=generate_code', [
            new Button($buttonLabel, 'submit')
        ]);
        ob_start();
        ?>

    <header>
        <h1>Tableau de bord : Gestion des Classes</h1>
    </header>

    <div class="card">
            <?= $alertCode ?>
    </div>

    <div class="form-area" style="margin-top: 20px;">
        <?= $form ?>
    </div>

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
    
