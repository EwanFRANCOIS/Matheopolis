<?php

namespace src\views;

use src\views\components\Button;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Paragraph;

class QuizView
{
    public function show(string|float|null $result = null): string
    {
        $Quiz = new Form('/index.php?action=quiz', [
            new Input('question1', '5 + 5 :', 'text', '', '', false),
            new Input('question2', '10 / 5 * 5 / 2 :', 'text', '', '', false),
            new Input('question3', '10x - 5 = 3 :', 'text', '', '', false),
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
        
        <h2>Quiz Example</h2>
        <?= $Quiz ?>

        <?= $resultParagraph ?>

        <?php
        return ob_get_clean();
    }
}