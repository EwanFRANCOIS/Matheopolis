<?php

namespace modules\views;

use modules\views\components\Paragraph;

class StudentProfileView
{
    public function show(?string $error = null): string
    {

        $info = new Paragraph([
            'Bienvenue sur votre profil élève'
        ]);

        $form = new Form('index.php?action=joinClass', [
            new Input('classCode', 'Code d\'une classe :', 'text', 'XXXXXX', '', true),
            new Button('Rejoindre', 'submit'),
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
    <h1>Votre Profil élève</h1>
</header>

<div class="card">
    <?= $info ?>
</div>

<div class="card notebook">
        <span class="small">CARNET D'APPRENTISSAGE</span>
        <div>
            <h2>Code d'une classe :</h2>
            <?= $form ?>
        </div>
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
