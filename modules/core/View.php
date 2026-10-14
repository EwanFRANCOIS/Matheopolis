<?php

namespace modules\core;

use modules\views\Layout;

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
