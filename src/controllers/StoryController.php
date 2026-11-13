<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;
use src\core\View;
use src\views\StoryView;

class StoryController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $n = isset($request->get['dialogue']) ? (int) $request->get['dialogue'] : 1;

        $vue = (new StoryView())->show($n);
        return $this->view->render('Histoire', $vue);
    }
}