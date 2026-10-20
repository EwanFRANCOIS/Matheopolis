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
    public function show(?string $error = null): string
    {
        $info = new Paragraph([
            'Si vous voulez accedez à l\'ensemble du site blah blah blah vendez votre âme et inscrivez vous :)'
        ]);

        $form = new Form('index.php?action=signup', [
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

<div class="card">
    <h2>Vous êtes un étudiant ?</h2>
    <?= $infoStudent ?>
</div>

        <?php
        return ob_get_clean();
    }
}
