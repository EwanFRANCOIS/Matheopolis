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
        private readonly ?string $createdCode = null,
        private readonly ?array $notes = []
    ) {}

    public function show(): string
    {
        $role = $this->role;
        $error = $this->error;
        $classInfo = $this->classInfo;
        $classes = $this->classes;
        $notes = $this->notes;

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

<?php if ($error) : ?>
    <div>
        <?= $alertError ?>
    </div>
<?php endif; ?>

<div class="cards equal">

    <div class="card full">
        <?= $info ?>
    </div>

    <?php if ($role === 'teacher') : ?>
        <div class="colonne">
            <div class="card notebook">
                <span class="small">CLASSES</span>
                <h2>Créer une nouvelle Classe</h2>
                <?= $formClass ?>
                <div>
                    <?= $alertCode ?>
                </div>
            </div>

            <div class="card parchment">
                <span class="small red">SUIVI</span>
                <h2>Vos classes</h2>

                <?php if (!empty($classes)) : ?>
                    <ul class="classes">
                        <?php foreach ($classes as $class) : ?>
                            <li>
                                <h3><?= $class['name'] ?></h3>
                                <p class="small red"><?= $class['cycle'] ?> - <?= $class['code'] ?></p>
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
        </div>

        <div class="colonne">
            <?php if (!empty($classes)) : ?>
                <div class="card notebook">
                    <span class="small">INSCRIPTION</span>
                    <h2>Créer un compte pour un élève</h2>
                    <?= $formCreateStudent ?>
                    
                    <h2 style="margin-top: 30px;">Importez votre classe</h2>
                    <?= $alertCSV ?>
                    <?= $formImportClass ?>
                </div>
            <?php else : ?>
                <div class="card notebook">
                    <span class="small">INSCRIPTION</span>
                    <h2>Gestion des élèves</h2>
                    <p>Créez d'abord une première classe pour débloquer l'ajout d'élèves et l'import CSV.</p>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif ($role === 'student') : ?>
        
        <div class="colonne">
            <div class="card notebook">
                <span class="small">CARNET D'APPRENTISSAGE</span>
                <h2>Informations sur votre classe</h2>
                    <div>
                        <h3>Votre classe : <?= $classInfo['name'] ?? '' ?></h3>
                        <p>Cycle : <?= $classInfo['cycle'] ?? '' ?></p>
                        <p>Code : <strong><?= $classInfo['classCode'] ?? '' ?></strong></p>
                    </div>

            </div>
        </div>

        <div class="colonne">
            <div class="card parchment">
                <span class="small red">PROGRESSION</span>
                <h2>Relevé des notes</h2>
                <?php if (!empty($notes)) : ?>
                    <ul class="notes">
                        <?php foreach ($notes as $note) : ?>
                            <li>
                                <h3><?= $note['title'] ?></h3>
                                <p>Score : <?= $note['score'] ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Vos résultats d'exercices apparaîtront ici.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card full parchment">
        <h2>Voulez-vous supprimer votre profil ?</h2>
        <?= $deleteInfo ?>
        <?= $deleteForm ?>
    </div>
</div>

        <?php
        return ob_get_clean();
    }
}