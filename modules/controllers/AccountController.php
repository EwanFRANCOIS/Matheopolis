<?php

namespace modules\controllers;

use modules\core\Response;

class AccountController
{
    public function execute(): Response
    {
        $vue = (new \modules\views\AccountView())->show();
        return (new Response(content: $vue));
    }
}
