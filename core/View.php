<?php

namespace src\core;

use src\views\Layout;

final class View
{
    public function __construct(private readonly ?string $userEmail)
    {
    }

    public function render(string $title, string $content, int $status = 200): Response
    {
        return (new Layout($title, $content, $this->userEmail))->show($status);
    }
}
