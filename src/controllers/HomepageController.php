<?php

namespace src\controllers;

use src\core\Response;
use src\core\Request;
use src\core\View;

class HomepageController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $vue = (new \src\views\HomepageView())->show();
        return $this->view->render('Bienvenue', $vue);
    }
}
