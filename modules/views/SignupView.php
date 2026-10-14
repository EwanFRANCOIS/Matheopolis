<?php

namespace modules\views;

use modules\views\components\Alert;
use modules\views\components\Button;
use modules\views\components\Form;
use modules\views\components\Input;
use modules\views\components\Link;
use modules\views\components\Paragraph;

class SignupView
{
    public function show(?string $error = null): string
    {
        $info = new Paragraph([
            'Si vous voulez accedez à l\'ensemble du site blah blah blah vendez votre âme et inscrivez vous :)'
        ]);

        $form = new Form('index.php?action=signup', [
            new Input('email', 'Votre adresse email :', 'email', 'exemple@domaine.fr', '', true),
            new Input('password', 'Votre Mot de Passe :', 'password', 'Mot de passe', '', true),
            new Input('verif', 'Vérification de votre Mot de Passe :', 'password', 'Verification', '', true),
            new Paragraph([
                new Button('Envoyer', 'submit', 'action', 'inscription'),
                new Button('Réinitialiser', 'reset'),
            ]),
        ]);

        $alert = $error !== null && $error !== '' ? new Alert([$error]) : new Alert([]);
        $link = new Link('index.php?action=login', 'Se connecter');

        ob_start();
        ?>

<header>
    <h1>S'inscrire</h1>
</header>

<div class="card">
    <?= $info ?>
</div>

<div class="form-area">
    <?= $form ?>
    <?= $alert ?>
    <?= $link ?>
</div>

        <?php
        return ob_get_clean();
    }
}
