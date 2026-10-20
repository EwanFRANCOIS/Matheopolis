<?php

namespace modules\controllers;

use modules\core\Request;
use modules\core\Response;
use modules\core\View;

class BookController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        if ($request->session('email') !== null || $request->session('username') !== null) {
            $vue = (new \modules\views\BookView())->show();
            return $this->view->render('Les livres', $vue);
        } else {
            return Response::redirect('index.php?action=login');
        }
    }
}
