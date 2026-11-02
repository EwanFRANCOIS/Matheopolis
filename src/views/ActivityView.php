<?php

namespace src\views;

use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Paragraph;
use src\views\components\Link;

class ActivityView
{
    public function show(string|float|null $result = null): string
    {

        $buttonCalculator = new Button(
            label: 'Calculatrice', 
            type: 'submit', 
            id: 'CalculatorId',
            class: 'btn jeu'
            );
        
        $linkQuiz = new link('/index.php?action=quiz', 'Quiz', 'btn jeu');
        $jeu2 = new link('/index.php?action=', 'jeu 2', 'btn jeu');
        $jeu3 = new link('/index.php?action=', 'jeu 3', 'btn jeu');
        $form = new Form('/index.php?action=activity', [
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

<div class="activity-container">
    <?= $linkQuiz->render() ?>
    <?= $buttonCalculator->render() ?>
    <?= $jeu2->render() ?>
    <?= $jeu3->render() ?>
</div>

<div id="d1" class="card form-area activity-panel" hidden>
    <h2>Calculatrice</h2>
    <?= $form ?>
    <?php if ($resultBlock !== null) : ?>
        <div class="card parchment">
            <?= $resultBlock ?>
        </div>
    <?php endif; ?>
</div>

<script>
    const btnCalculator = document.getElementById('CalculatorId');
    const calculator = document.getElementById('d1');

    btnCalculator.addEventListener('click', () => {
        calculator.hidden = !calculator.hidden;
    });
</script>

        <?php
        return ob_get_clean();
    }
}
