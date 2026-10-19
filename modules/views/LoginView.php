<?php

namespace modules\views;

use modules\views\components\Alert;
use modules\views\components\Button;
use modules\views\components\Form;
use modules\views\components\Input;
use modules\views\components\Link;
use modules\views\components\Paragraph;

class LoginView
{
    public function show(?string $error = null): string
    {
        $info = new Paragraph([
            'Vous avez déjà vendu votre âme ? Connectez vous pour accéder à l\'ensemble du site.'
        ]);

        $form = new Form('index.php?action=login', [
            new Input('login', 'Votre adresse email / Pseudonyme élève :', 'text', 'exemple@domaine.fr / Pseudo', '', true),
            new Input('password', 'Votre Mot de Passe :', 'password', 'Mot de passe', '', true),
            new Paragraph([
                new Button('Envoyer', 'submit', 'action', 'logUser'),
                new Button('Réinitialiser', 'reset'),
            ]),
        ]);

        $alert = $error !== null && $error !== '' ? new Alert([$error]) : new Alert([]);
        $linkForgot = new Paragraph([
            new Link('index.php?action=forgot_pwd', 'Mot de passe oublié ?')
        ]);
        $linkSignup = new Paragraph([
            new Link('index.php?action=signup', 's\'inscrire')
        ]);

        ob_start();
        ?>

<header>
    <h1>Connexion</h1>
</header>

<div class="card">
    <?= $info ?>
</div>

<div class="form-area">
    <?= $form ?>
    <?= $alert ?>
    <?= $linkForgot ?>
    <?= $linkSignup ?>
</div>

        <?php
        return ob_get_clean();
    }
}
