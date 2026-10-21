<?php

namespace src\views\components;

final class Form extends Component
{
    public function __construct(
        private readonly string $action,
        private readonly array $children,
        private readonly string $method = 'post'
    ) {
    }

    public function render(): string
    {
        $html = '<form action="' . $this->e($this->action) . '" method="' . $this->e($this->method) . '">';
        $html .= $this->renderAll($this->children);
        $html .= '</form>';

        return $html;
    }
}
