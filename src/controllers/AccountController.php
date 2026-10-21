<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;
use src\core\View;

class AccountController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $vue = (new \src\views\AccountView())->show();
        return $this->view->render('Profil', $vue);
    }
}
