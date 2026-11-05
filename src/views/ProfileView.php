<?php

namespace src\views;

use src\views\components\Paragraph;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Select;
use src\views\components\Button;
use src\views\components\Alert;
use src\views\components\ListComponent;


class ProfileView
{
    public function __construct(
        private readonly ?string $role,
        private readonly ?string $error = null,
        private readonly string|array|null $classInfo = null,
        private readonly ?array $students = [],
        private readonly ?array $classes = [],
        private readonly ?string $createdCode = null
    ) {}

    public function show(): string
    {
        $role = $this->role;
        $error = $this->error;
        $classInfo = $this->classInfo;
        $students = $this->students;
        $classes = $this->classes;

        $info = new Paragraph([
            'Bienvenue sur votre profil'
        ]);

        // Gestion des messages d'erreur
        $messages = [];
        if ($error) {
            $messages[] = $error;
        }
        $alertError = new Alert($messages);

        $alertCode = $this->createdCode
            ? new Alert(["Code de classe : " . $this->createdCode], true)
            : new Alert(["Vous n'avez pas encore généré de code pour votre classe."]);

        // Bouton pour générer ou régénérer le code de classe
        $buttonLabel = !empty($classes)
            ? 'Générer un nouveau code' 
            : 'Créer ma classe (Générer un code)';

        $classOptions = [];
        foreach ($classes as $class) {
            $classOptions[$class['code']] = $class['name'] . ' (' . $class['cycle'] . ')';
        }


        $formClass = new Form('index.php?action=profile&subaction=generate_code', [
            new Input('className', 'Nom de la classe :', 'text', 'Nom de la classe (ex: Seconde B)', '', true),
            new Select('level', 'Niveau de la classe :', [
                'college' => 'Cycle Collège',
                'seconde' => 'Lycée : Seconde',
                'premiere' => 'Lycée : Première',
                'terminale' => 'Lycée : Terminale',
            ]),
            new Button($buttonLabel, 'submit'),
        ]);

        // Formulaire de création de compte élève par l'enseignant
        $formCreateStudent = new Form('index.php?action=profile', [
            new Input('action_type', '', 'hidden', '', 'create_student'),
            new Input('pseudo', 'Pseudo de l\'élève :', 'text', 'Entrez le pseudo', '', true),
            new Input('password', 'Mot de passe temporaire :', 'password', 'Mot de passe', '', true),
            new Select('code', 'Classe :', $classOptions),
            new Button('Créer l\'élève', 'submit')
        ]);

        $alertCSV = new Alert(["Le fichier CSV doit contenir les colonnes suivantes : nom, prenom"], true);

        $formImportClass = new Form(
            'index.php?action=profile',
            [
                new Input('action_type', '', 'hidden', '', 'import_csv'),
                new Select('code', 'Classe :', $classOptions),
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

<div class="cards">

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
        <?php if (!empty($classes)) : ?>
        <div class="card notebook">
            <span class="small">INSCRIPTION</span>
            <h2>Créer un compte pour un élève</h2>
            <?= $formCreateStudent ?>
            <h2>Importez votre classe</h2>
            <?= $alertCSV ?>
            <?= $formImportClass ?>
        </div>
        <?php endif; ?>

        <!-- Liste des classes et de leurs élèves -->
        <div class="card parchment">
            <span class="small red">SUIVI</span>
            <h2>Vos classes</h2>

            <?php if (!empty($classes)) : ?>
                <ul class="classes">
                    <?php foreach ($classes as $class) : ?>
                        <li>
                            <h3><?= $class['name'] ?></h3>
                            <p class="small red"> <?= $class['cycle'] ?> - <?= $class['code'] ?></p>
                            <?php if (!empty($class['students'])) : ?>
                                <?= new ListComponent($class['students']) ?>
                            <?php else : ?>
                                <p><em>Aucun élève inscrit.</em></p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Aucune classe n'a été créée pour le moment.</p>
            <?php endif; ?>
        </div>

        <?php
    } else if ($role === 'student') {
        ?>
        <div class="card notebook">
            <span class="small">CARNET D'APPRENTISSAGE</span>
            <h2>Informations sur votre classe</h2>
            <div>
                <h2>Votre classe : <?= $classInfo['name'] ?? '' ?></h2>
                <p>Cycle : <?= $classInfo['cycle'] ?? '' ?></p>
                <p>Code : <strong><?= $classInfo['classCode'] ?? '' ?></strong></p>
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
</div>

        <?php
        return ob_get_clean();
    }
}