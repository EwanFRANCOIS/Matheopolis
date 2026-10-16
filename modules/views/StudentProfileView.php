<?php

namespace modules\views;

use modules\views\components\Paragraph;

class StudentProfileView
{
    public function show(?string $error = null): string
    {

        $info = new Paragraph([
            'Bienvenue sur votre profil élève'
        ]);

        ob_start();
        ?>

<header>
    <h1>Profil de l'élève</h1>
</header>

<div class="card">
    <?= $info ?>
</div>

        <?php
        return ob_get_clean();
    }
}
