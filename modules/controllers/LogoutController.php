<?php

namespace modules\controllers;

use modules\core\Request;
use modules\core\Response;

class LogoutController
{
    public function execute(Request $request): Response
    {
        $request->destroySession();

        return Response::redirect('index.php?action=home');
    }
}
