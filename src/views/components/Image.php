<?php

namespace src\views\components;

final class Image extends Component
{
    public function __construct(
        private readonly string $src,
        private readonly string $alt = '',
        private readonly string $class = ''
    ) {
    }

    public function render(): string
    {
        return '<img src="' . $this->e($this->src) . '" alt="' . $this->e($this->alt) . '" class="' . $this->e($this->class) . '">';
    }
}