<?php

namespace modules\controllers;

use modules\core\Response;

class BookController
{
    public function execute(): Response
    {
        if (isset($_SESSION['email'])) {
            $vue = (new \modules\views\BookView())->show();
            return new Response(content: $vue);
        } else {
            return Response::redirect('index.php?action=login');
        }
    }
}
