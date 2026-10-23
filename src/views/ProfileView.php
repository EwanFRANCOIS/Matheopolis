<?php

namespace src\views;

use src\views\components\Paragraph;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Select;
use src\views\components\Button;
use src\views\components\Alert;


class ProfileView
{
    public function show(?string $role, ?string $error = null, ?string $codeClasse = null, ?array $students = []): string
    {

        $info = new Paragraph([
            'Bienvenue sur votre profil'
        ]);

        // Gestion des messages d'erreur
        $messages = [];
        if ($error) {
            $messages[] = $error;
        }
        $alertError = new Alert($messages);

        $codeMessage = $codeClasse 
            ? $alertCode = new Alert(["Code de classe : " . $codeClasse], true)
            : $alertCode = new Alert(["Vous n'avez pas encore généré de code pour votre classe."]);

        // Bouton pour générer ou régénérer le code de classe
        $buttonLabel = $codeClasse 
            ? 'Générer un nouveau code' 
            : 'Créer ma classe (Générer un code)';


        $formClass = new Form('index.php?action=profile&subaction=generate_code', [
            new Input('className', 'Nom de la classe :', 'text', 'Nom de la classe (ex: Seconde B)', '', true),
            new Select('level', 'Niveau de la classe :', ['Cycle Collège', 'Lycée : Seconde', 'Lycée : Première', 'Lycée : Terminale']),
            new Button($buttonLabel, 'submit'),
        ]);

        // Formulaire de création de compte élève par l'enseignant
        $formCreateStudent = new Form('index.php?action=profile', [
            new Input('action_type', '', 'hidden', '', 'create_student'),
            new Input('pseudo', 'Pseudo de l\'élève :', 'text', 'Entrez le pseudo', '', true),
            new Input('password', 'Mot de passe temporaire :', 'password', 'Mot de passe', '', true),
            new Button('Créer l\'élève', 'submit')
        ]);

        $alertCSV = new Alert(["Le fichier CSV doit contenir les colonnes suivantes : nom, prenom"], true);

        $formImportClass = new Form(
            'index.php?action=profile',
            [
                new Input('action_type', '', 'hidden', '', 'import_csv'),
                new Input('fichier_eleves', 'Sélectionner le fichier CSV :', 'file', '', '', true),
                new Button('Importer les élèves', 'submit')
            ],
            'post',
            hasFiles: true
        );

        $deleteInfo = new Paragraph([
            'Si vous supprimez votre profil, toutes vos données seront définitivement perdues.'
        ]);
        $deleteForm = new Form('index.php?action=delete', [
            new Button('Supprimer mon profil', 'submit', 'action', 'delete', 'red'),
        ]);

        ob_start();
        ?>

<header>
    <span class="small">VOUS VOICI SUR</span>
    <h1>Votre Profil
        <?= $role === 'teacher' ? 'Enseignant' : ($role === 'student' ? 'Etudiant' : '') ?>
    </h1>
</header>

<?php if ($error): ?>
    <div>
        <?= $alertError ?>
    </div>
<?php endif; ?>

<div class="card">
    <?= $info ?>
</div>

<?php
if ($role === 'teacher') {
    ?>
    <div class="card notebook">
        <span class="small">CLASSES</span>
        <h2>Creer une nouvelle Classe</h2>
        <?= $formClass ?>
        <div>
            <?= $alertCode ?>
        </div>
    </div>

    <!-- création de compte pour élève (si code classe existe)-->
    <?php if ($codeClasse) : ?>
    <div class="card notebook">
        <span class="small">INSCRIPTION</span>
        <h2>Créer un compte pour un élève</h2>
        <?= $formCreateStudent ?>
        <h2>Importez votre classe</h2>
        <?= $alertCSV ?>
        <?= $formImportClass ?>
    </div>
    <?php endif; ?>

    <!-- Liste eleves associés au prof -->
    <div class="card notebook">
        <span class="small">SUIVI</span>
        <h2>Liste de vos élèves inscrits :</h2>

        <?php if (empty($students)) : ?>
            <p><em>Aucun élève n'a rejoint votre classe pour l'instant.</em></p>
        <?php else : ?>
            <ul style="list-style-type: disc; padding-left: 20px;">
                <?php foreach ($students as $student) : ?>
                     <!-- ENT_QUOTES permet de convertir les guillemets en chaine de caractere (htmlspecialchars ne le fait pas par défaut apparemment) -->
                    <li>Élève : 
                        <strong><?=htmlspecialchars($student['username'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>



    <?php
} else if ($role === 'student') {
    ?>
    <div class="card notebook">
        <span class="small">CARNET D'APPRENTISSAGE</span>
        <h2>Informations sur votre classe</h2>
        <div>
            <h2>Votre code de classe : <?= $codeClasse ?></h2>
        </div>
    </div>
    <?php
}
?>

<div class="card parchment">
    <h2>Voulez-vous supprimer votre profil ?</h2>
    <?= $deleteInfo ?>
    <?= $deleteForm ?>
</div>

        <?php
        return ob_get_clean();
    }
}