<?php

namespace modules\controllers;

use modules\core\Response;
use modules\core\Request;
use modules\core\View;

class HomepageController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $vue = (new \modules\views\HomepageView())->show();
        return $this->view->render('Bienvenue', $vue);
    }
}
