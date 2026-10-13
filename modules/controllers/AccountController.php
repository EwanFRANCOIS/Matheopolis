<?php

namespace modules\controllers;

class AccountController
{
    public function execute(): void
    {
        (new \modules\views\AccountView())->show();
    }
}
