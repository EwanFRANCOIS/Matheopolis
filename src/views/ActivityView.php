<?php

namespace src\views;

use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Paragraph;
use src\views\components\Tile;

class ActivityView
{
    public function __construct(private readonly string|float|null $result = null)
    {
    }

    public function show(): string
    {
        $result = $this->result;

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

        $tiles = [
            new Tile(
                href: '/index.php?action=quiz',
                symbol: '?',
                title: 'Quiz',
                description: "Testez vos connaissances sur l'univers et les énigmes",
                class: 'quiz'
            ),
            new Tile(
                href: null,
                symbol: '±',
                title: 'Calculatrice',
                description: 'Effectuez vos opérations arithmétiques rapidement',
                class: 'calculator',
                id: 'CalculatorId'
            ),
            new Tile(
                href: '/index.php?action=conversion',
                symbol: '⇄',
                title: 'Conversion',
                description: 'Convertissez les unités et les systèmes de mesure',
                class: 'conversion'
            ),
            new Tile(
                href: '/index.php?action=',
                symbol: 'Δ',
                title: 'Jeu 3',
                description: 'Un nouveau défi mathématique arrive bientôt'
            ),
        ];

        ob_start();
        ?>

<header>
    <span class="small">EXERCICES</span>
    <h1>Activités</h1>
</header>

<div class="tile-grid">
    <?= implode('', $tiles) ?>
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