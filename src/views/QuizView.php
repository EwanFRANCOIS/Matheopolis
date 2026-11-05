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
        $test = new Paragraph([
            'Coucou, nina'
        ]);

        ob_start();
        ?>
        
        <h2>coucou</h2>
        <?= $test ?>

        <?php
        return ob_get_clean();
    }
}