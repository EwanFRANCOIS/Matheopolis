<?php

namespace modules\controllers;

class LogoutController
{
    public function execute(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
