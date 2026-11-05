<?php

namespace src\views;

use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Paragraph;

class ConversionView
{
    public function show(string|float|null $result = null): string
    {
        $ConversionQuiz = new Form('/index.php?action=conversion', [
            new Input('question1', 'Convertissez 500 mètre en kilomètres :', 'text', '', '', false),
            new Input('question2', 'Convertissez 0.3 dl en litres :', 'text', '', '', false),
            new Input('question3', 'Convertissez 0.003 g en tonnes :', 'text', '', '', false),
            new Paragraph([
                new Button('Soumettre', 'submit'),
            ]),
        ]);

        $resultParagraph = '';
        if ($result !== null) {
            $resultParagraph = new Paragraph([
                "Votre score est : " . htmlspecialchars((string)$result) . "/3"
            ]);
        }

        ob_start();
        ?>
        
        <h2>Conversion Example</h2>
        <?= $ConversionQuiz ?>

        <?= $resultParagraph ?>

        <?php
        return ob_get_clean();
    }
}