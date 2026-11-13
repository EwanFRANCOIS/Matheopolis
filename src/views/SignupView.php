<?php

namespace src\views;

use src\views\components\Alert;
use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Link;
use src\views\components\Paragraph;

class SignupView
{
    public function __construct(private readonly ?string $error = null)
    {
    }

    public function show(): string
    {
        $error = $this->error;
        $info = new Paragraph([
            'Si vous voulez accéder à l\'ensemble du site inscrivez-vous !'
        ]);

        $form = new Form('/index.php?action=signup', [
            new Input('email', 'Votre adresse email :', 'email', 'exemple@domaine.fr', '', true),
            new Input('password', 'Votre Mot de Passe :', 'password', 'Mot de passe', '', true),
            new Input('verif', 'Vérification de votre Mot de Passe :', 'password', 'Verification', '', true),
            new Input('type', 'Je suis Enseignant :', 'checkbox', '', '', false, 'checkbox'),
            new Paragraph([
                new Button('Envoyer', 'submit', 'action', 'inscription'),
                new Button('Réinitialiser', 'reset'),
            ]),
        ]);

        $infoStudent = new Paragraph([
            'Vous pouvez rejoindre une classe existante en utilisant le code de la classe fourni par votre enseignant.'
        ]);

        $alert = !empty($error) ? new Alert([$error]) : null;
        $link = new Paragraph([
            new Link('/index.php?action=login', 'Déjà inscrit ? Se connecter')
        ]);

        ob_start();
        ?>

<header>
    <span class="small">REJOINDRE L'AVENTURE</span>
    <h1>S'inscrire</h1>
</header>

<div class="colonne" style="max-width: 680px; width: 100%; margin: 0 auto;">
    <div class="card notebook watermark-card">
        <span class="small">NOUVEAU COMPTE</span>
        <h2>Créer un profil</h2>
        <?= $info ?>

        <?php if ($alert !== null) : ?>
            <?= $alert ?>
        <?php endif; ?>

        <div class="form-area">
            <?= $form ?>
            <?= $link ?>
        </div>
    </div>

    <div class="card parchment">
        <span class="small red">ACCÈS ÉLÈVE</span>
        <h2>Vous êtes un étudiant ?</h2>
        <?= $infoStudent ?>
    </div>
</div>

        <?php
        return ob_get_clean();
    }
}