<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;

class LogoutController
{
    public function execute(Request $request): Response
    {
        $request->destroySession();

        return Response::redirect('/index.php?action=homepage');
    }
}
