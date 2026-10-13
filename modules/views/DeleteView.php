<?php

namespace modules\views;

use modules\views\components\Alert;
use modules\views\components\Button;
use modules\views\components\Form;
use modules\views\components\Input;
use modules\views\components\Paragraph;

class DeleteView
{
    public function show(?string $error = null): string
    {
        $info = new Paragraph([
            'Voulez vous vraiment supprimer votre compte ? Veuillez écrire votre mot de passe pour confirmer sa suppression.'
        ]);

        $form = new Form('index.php?action=delete', [
            new Input('password', 'Votre Mot de Passe :', 'password', 'Mot de passe', '', true),
            new Button('Envoyer', 'submit', 'action', 'deleteUser'),
        ]);

        $alert = $error !== null && $error !== '' ? new Alert([$error]) : new Alert([]);

        ob_start();
        ?>

<header>
    <h1>Suppression</h1>
</header>

<div class="card">
    <?= $info ?>
</div>

<div class="form-area">
    <?= $form ?>
    <?= $alert ?>
</div>

        <?php
        return ob_get_clean();
    }
}
