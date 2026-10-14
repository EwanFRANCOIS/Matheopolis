<?php

namespace modules\controllers;

use modules\core\Request;
use modules\core\Response;
use modules\core\View;

class AccountController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $vue = (new \modules\views\AccountView())->show();
        return $this->view->render('Profil', $vue);
    }
}
