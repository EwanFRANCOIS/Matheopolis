<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;
use src\core\View;
use src\views\QuizView;

class QuizController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $vue = (new QuizView())->show();
        return $this->view->render('Quiz', $vue);
    }
}
