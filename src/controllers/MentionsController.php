<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;
use src\core\View;
use src\views\MentionsView;

class MentionsController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $vue = (new MentionsView())->show();
        return $this->view->render('Mentions légales', $vue);
    }
}

