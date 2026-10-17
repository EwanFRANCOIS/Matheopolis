<?php

namespace modules\controllers;

use modules\views\StoryView;

class StoryController
{
    public function index(): void
    {
        $view = new StoryView();
        echo $view->show();
    }
}