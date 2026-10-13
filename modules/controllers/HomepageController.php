<?php

namespace modules\controllers;

class HomepageController
{
    public function execute(): void
    {
        (new \modules\views\HomepageView())->show();
    }
}
