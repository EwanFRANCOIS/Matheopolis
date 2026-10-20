<?php

namespace src\views;

use src\views\components\Alert;
use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Paragraph;

class DeleteView
{
    public function show(?string $error = null): string
    {
        $info = new Paragraph([
            'Voulez vous vraiment supprimer votre compte ? Veuillez écrire votre mot de passe pour confirmer sa suppression.'
        ]);

        $form = new Form('index.php?action=delete', [
            new Input('password', 'Votre Mot de Passe :', 'password', 'Mot de passe', '', true),
            new Button('Envoyer', 'submit', 'action', 'deleteUser', 'red'),
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
