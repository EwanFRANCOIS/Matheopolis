<?php

namespace modules\views\components;

final class Strong extends Component
{
    public function __construct(
        private readonly string $text
    ) {
    }

    public function render(): string
    {
        return '<strong>' . $this->e($this->text) . '</strong>';
    }
}
