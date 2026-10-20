<?php

namespace src\views;

use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Paragraph;

class ActivityView
{
    public function show(string|float|null $result = null): string
    {
        $form = new Form('index.php?action=activity', [
            new Input('op1', 'Opérande 1 :', 'text', '', '', true),
            new Input('op2', 'Opérande 2 :', 'text', '', '', true),
            new Paragraph([
                new Button('+', 'submit', 'operation', '+'),
                new Button('-', 'submit', 'operation', '-'),
                new Button('×', 'submit', 'operation', '*'),
                new Button('÷', 'submit', 'operation', '/'),
            ]),
            new Button('Réinitialiser', 'reset'),
        ]);

        $resultBlock = $result !== null ? new Paragraph([(string) $result]) : null;

        ob_start();
        ?>

<header>
    <h1>Activités</h1>
</header>

<div class="card form-area">
    <h2>Calculatrice</h2>
    <?= $form ?>
    <?php if ($resultBlock !== null) : ?>
        <div class="card parchment">
            <?= $resultBlock ?>
        </div>
    <?php endif; ?>
</div>

        <?php
        return ob_get_clean();
    }
}
