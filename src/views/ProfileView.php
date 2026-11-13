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
    ) {
    }

    public function show(): string
    {
        $role = $this->role;
        $error = $this->error;
        $classInfo = $this->classInfo;
        $classes = $this->classes;

        $messages = [];
        if ($error) {
            $messages[] = $error;
        }
        $alertError = new Alert($messages);

        $alertCode = $this->createdCode
            ? new Alert(["Code généré : " . $this->createdCode], true)
            : null;

        $buttonLabel = !empty($classes)
            ? 'Générer un nouveau code'
            : 'Créer ma classe';

        $classOptions = [];
        foreach ($classes as $class) {
            $classOptions[$class['code']] = $class['name'] . ' (' . $class['cycle'] . ')';
        }

        $formClass = new Form('index.php?action=profile&subaction=generate_code', [
            new Input('className', 'Nom de la classe :', 'text', 'Ex : Seconde B', '', true),
            new Select('level', 'Niveau :', [
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
            new Button('Créer l\'élève', 'submit'),
        ]);

        $alertCSV = new Alert(["Format attendu : colonnes nom, prenom"], true);

        $formImportClass = new Form(
            'index.php?action=profile',
            [
                new Input('action_type', '', 'hidden', '', 'import_csv'),
                new Select('code', 'Classe :', $classOptions),
                new Input('fichier_eleves', 'Fichier CSV :', 'file', '', '', true),
                new Button('Importer la liste', 'submit'),
            ],
            'post',
            hasFiles: true
        );

        $deleteInfo = new Paragraph([
            'Attention : la suppression de votre profil effacera définitivement toutes vos données.'
        ]);
        $deleteForm = new Form('index.php?action=delete', [
            new Button('Supprimer mon profil', 'submit', 'action', 'delete', 'red'),
        ]);

        ob_start();
        ?>

<header>
    <span class="small">ESPACE PERSONNEL</span>
    <h1>Profil <?= $role === 'teacher' ? 'Enseignant' : ($role === 'student' ? 'Étudiant' : '') ?></h1>
</header>

<?php if ($error) : ?>
    <?= $alertError ?>
<?php endif; ?>

<div class="cards equal">

    <?php if ($role === 'teacher') : ?>

        <div class="colonne">
            <div class="card notebook watermark-card">
                <span class="small">NOUVELLE CLASSE</span>
                <h2>Créer une classe</h2>
                <?= $formClass ?>
                <?php if ($alertCode !== null) : ?>
                    <div style="margin-top: 16px;">
                        <?= $alertCode ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card parchment">
                <span class="small red">REGISTRE</span>
                <h2>Vos classes</h2>

                <?php if (!empty($classes)) : ?>
                    <ul class="class-list">
                        <?php foreach ($classes as $class) : ?>
                            <li class="class-item">
                                <div class="class-header">
                                    <h3><?= htmlspecialchars($class['name']) ?></h3>
                                    <span class="code-badge"><?= htmlspecialchars($class['code']) ?></span>
                                </div>
                                <p class="small red"><?= htmlspecialchars($class['cycle']) ?></p>
                                <?php if (!empty($class['students'])) : ?>
                                    <?= new ListComponent($class['students']) ?>
                                <?php else : ?>
                                    <p><em>Aucun élève inscrit pour le moment.</em></p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Aucune classe enregistrée.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="colonne">
            <?php if (!empty($classes)) : ?>
                <div class="card notebook">
                    <span class="small">INSCRIPTION</span>
                    <h2>Ajouter un élève</h2>
                    <?= $formCreateStudent ?>

                    <h2 style="margin-top: 32px;">Importer un fichier CSV</h2>
                    <?= $alertCSV ?>
                    <?= $formImportClass ?>
                </div>
            <?php else : ?>
                <div class="card notebook">
                    <span class="small">INSCRIPTION</span>
                    <h2>Gestion des élèves</h2>
                    <p>Créez d'abord une classe à gauche pour générer un code et inscrire vos élèves.</p>
                </div>
            <?php endif; ?>

            <div class="card danger-zone">
                <span class="small">PARAMÈTRES</span>
                <h2>Zone critique</h2>
                <?= $deleteInfo ?>
                <?= $deleteForm ?>
            </div>
        </div>

    <?php elseif ($role === 'student') : ?>

        <div class="colonne">
            <div class="card notebook watermark-card">
                <span class="small">CARNET D'APPRENTISSAGE</span>
                <h2>Votre classe</h2>
                <?php if (is_array($classInfo) && !empty($classInfo)) : ?>
                    <div class="class-header">
                        <h3><?= htmlspecialchars($classInfo['name'] ?? '') ?></h3>
                        <span class="code-badge"><?= htmlspecialchars($classInfo['classCode'] ?? '') ?></span>
                    </div>
                    <p>Cycle : <strong><?= htmlspecialchars($classInfo['cycle'] ?? '') ?></strong></p>
                <?php else : ?>
                    <p>Vous n'êtes rattaché à aucune classe pour le moment.</p>
                <?php endif; ?>
            </div>

            <div class="card danger-zone">
                <span class="small">PARAMÈTRES</span>
                <h2>Zone critique</h2>
                <?= $deleteInfo ?>
                <?= $deleteForm ?>
            </div>
        </div>

        <div class="colonne">
            <div class="card parchment">
                <span class="small red">SUIVI</span>
                <h2>Relevé des épreuves</h2>
                <p>Vos prochains scores de quiz et défis mathématiques apparaîtront ici.</p>
            </div>
        </div>

    <?php endif; ?>

</div>

        <?php
        return ob_get_clean();
    }
}