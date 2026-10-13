<?php

namespace modules\controllers;

class BookController
{
    public function execute(): void
    {
        if (isset($_SESSION['email'])) {
            (new \modules\views\BookView())->show();
        } else {
            header('location: index.php?action=account');
            exit();
        }
    }
}
