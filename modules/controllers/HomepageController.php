<?php

namespace modules\controllers;

use modules\core\Response;
use modules\core\Request;

class HomepageController
{
    public function execute(Request $request): Response
    {
        $vue = (new \modules\views\HomepageView())->show();
        return (new Response(content: $vue));
    }
}
