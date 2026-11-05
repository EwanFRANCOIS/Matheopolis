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

        $error = null;

        $question1 = '';
        $question2 = '';
        $question3 = '';

        if ($request->method === 'POST') {
            $question1 = trim((string)$request->post('question1'));
            $question2 = trim((string)$request->post('question2'));
            $question3 = trim((string)$request->post('question3'));
        }
        $score = 0;

        if ($question1 === '10') {
            $score++;
        }

        if ($question2 === '5') {
            $score++;
        }

        if ($question3 === '0.8') {
            $score++;
        }
        
        $vue = (new QuizView())->show($score);
        return $this->view->render('Quiz', $vue);
    }
} 
    