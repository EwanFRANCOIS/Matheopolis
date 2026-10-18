<?php

namespace modules\views;

use modules\views\components\Paragraph;

class UnknownProfileView
{
    public function show(?string $error = null): string
    {

        $info = new Paragraph([
            'Bienvenue sur votre profil'
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
    <h1>Votre Profil</h1>
</header>

<div class="card">
    <?= $info ?>
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