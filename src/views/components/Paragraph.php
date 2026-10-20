<?php

namespace src\views\components;

final class Paragraph extends Component
{
    public function __construct(
        private readonly array $children
    ) {
    }

    public function render(): string
    {
        return '<p>' . $this->renderAll($this->children) . '</p>';
    }
}
