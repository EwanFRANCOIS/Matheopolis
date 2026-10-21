<?php

namespace src\views\components;

final class Link extends Component
{
    public function __construct(
        private readonly string $href,
        private readonly string $text
    ) {
    }

    public function render(): string
    {
        return '<a href="' . $this->e($this->href) . '">' . $this->e($this->text) . '</a>';
    }
}
