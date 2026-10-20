<?php

namespace src\views;

use src\views\components\Paragraph;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Select;
use src\views\components\Button;

class ProfileView
{
    public function show(?string $role, ?string $error = null): string
    {

        $info = new Paragraph([
            'Bienvenue sur votre profil'
        ]);

        $formClass = new Form('/index.php?action=createClass', [
            new Input('className', 'Nom de la classe :', 'text', 'Nom de la classe (ex: Seconde B)', '', true),
            new Select('level', 'Niveau de la classe :', ['Cycle Collège', 'Lycée : Seconde', 'Lycée : Première', 'Lycée : Terminale']),
            new Button('Créer une classe', 'submit'),
        ]);

        $formJoin = new Form('/index.php?action=joinClass', [
            new Input('classCode', 'Code d\'une classe :', 'text', 'XXXXXX', '', true),
            new Button('Rejoindre', 'submit'),
        ]);

        $deleteInfo = new Paragraph([
            'Si vous supprimez votre profil, toutes vos données seront définitivement perdues.'
        ]);
        $deleteForm = new Form('/index.php?action=delete', [
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
    </div>
    <?php
} else if ($role === 'student') {
    ?>
    <div class="card notebook">
        <span class="small">CARNET D'APPRENTISSAGE</span>
        <div>
            <h2>Code d'une classe :</h2>
            <?= $formJoin ?>
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