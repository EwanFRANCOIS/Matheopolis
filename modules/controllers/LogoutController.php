<?php

namespace modules\controllers;

use modules\core\Request;
use modules\core\Response;

class LogoutController
{
    public function execute(): Response
    {
        Request::destroySession();

        return Response::redirect('index.php?action=home');
    }
}
