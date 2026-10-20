<?php

namespace src\views;

use src\views\components\Link;
use src\views\components\Paragraph;

class AccountView
{
    public function show(): string
    {
        $free = new Paragraph(['Vous ne possédez pas de compte, créez en un :']);
        $signup = new Link('/index.php?action=signup', 'Inscrivez-vous');

        $already = new Paragraph(['Vous avez déjà un compte, connectez vous :']);
        $login = new Link('/index.php?action=login', 'Connectez-vous');

        ob_start();
        ?>

<header>
    <h1>Compte</h1>
</header>

<div class="card">
    <?= $free ?>
    <?= $signup ?>
</div>

<div class="card">
    <?= $already ?>
    <?= $login ?>
</div>

        <?php
        return ob_get_clean();
    }
}
