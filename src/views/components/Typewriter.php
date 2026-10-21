<?php

namespace src\views\components;

final class Typewriter extends Component
{
    public function __construct(
        private readonly string $text,
        private readonly int $speed = 50 //ms
    ) {
    }

    public function render(): string
    {
        return sprintf(
            '<span class="typewriter" data-text="%s" data-speed="%d"></span>',
            htmlspecialchars($this->text, ENT_QUOTES, 'UTF-8'),
            $this->speed
        );
    }
}