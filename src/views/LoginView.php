<?php

namespace src\views;

use src\views\components\Alert;
use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Link;
use src\views\components\Paragraph;

class LoginView
{
    public function __construct(private readonly ?string $error = null)
    {
    }

    public function show(): string
    {
        $error = $this->error;
        $info = new Paragraph([
            'Vous avez déjà rejoint l\'aventure ! Connectez-vous pour accéder à l\'ensemble du site.'
        ]);

        $form = new Form('/index.php?action=login', [
            new Input('login', 'Votre adresse email / Pseudonyme élève :', 'text', 'exemple@domaine.fr / Pseudo', '', true),
            new Input('password', 'Votre Mot de Passe :', 'password', 'Mot de passe', '', true),
            new Paragraph([
                new Button('Envoyer', 'submit', 'action', 'logUser'),
                new Button('Réinitialiser', 'reset'),
            ]),
        ]);

        $alert = !empty($error) ? new Alert([$error]) : null;
        $linkForgot = new Paragraph([
            new Link('/index.php?action=forgotpassword', 'Mot de passe oublié ?')
        ]);
        $linkSignup = new Paragraph([
            new Link('/index.php?action=signup', 'Pas encore de compte ? S\'inscrire')
        ]);

        ob_start();
        ?>

<header>
    <span class="small">ACCÈS AUX ARCHIVES</span>
    <h1>Connexion</h1>
</header>

<div class="colonne" style="max-width: 680px; width: 100%; margin: 0 auto;">
    <div class="card notebook watermark-card">
        <span class="small">IDENTIFICATION</span>
        <h2>Bon retour parmi nous</h2>
        <?= $info ?>

        <?php if ($alert !== null) : ?>
            <?= $alert ?>
        <?php endif; ?>

        <div class="form-area">
            <?= $form ?>
            <?= $linkForgot ?>
            <?= $linkSignup ?>
        </div>
    </div>
</div>

        <?php
        return ob_get_clean();
    }
}