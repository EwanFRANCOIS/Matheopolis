<?php

namespace modules\views;

use modules\views\components\Paragraph;

class TeacherProfileView
{
    public function show(?string $error = null): string
    {

        $info = new Paragraph([
            'Bienvenue sur votre profil enseignant'
        ]);

        $form = new Form('index.php?action=createClass', [
            new Input('className', 'Nom de la classe :', 'text', 'Nom de la classe (ex: Seconde B)', '', true),
            new Select('level', 'Niveau de la classe :', ['Cycle Collège', 'Lycée : Seconde', 'Lycée : Première', 'Lycée : Terminale']),
            new Button('Créer une classe', 'submit'),
        ]);

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
    <h1>Votre Profil enseignant</h1>
</header>

<div class="card">
    <?= $info ?>
</div>

<div class="card notebook">
    <span class="small">CLASSES</span>
    <h2>Creer une nouvelle Classe</h2>

    <?= $form ?>
</div>

<div class="card parchment">
    <h2>Voulez-vous supprimer votre profil ?</h2>
    <?= $deleteInfo ?>
    <?= $deleteForm ?>
</div>

        <?php
        return ob_get_clean();
    }
}