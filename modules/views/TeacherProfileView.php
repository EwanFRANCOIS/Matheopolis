<?php

namespace modules\views;

use modules\views\components\Paragraph;

class TeacherProfileView
{
    public function show(?string $error = null): string
    {

        $info = new Paragraph([
            'Bienvenue sur votre profil enseignant'
        ]);

        ob_start();
        ?>

<header>
    <h1>Profil de l'enseignant</h1>
</header>

<div class="card">
    <?= $info ?>
</div>

        <?php
        return ob_get_clean();
    }
}