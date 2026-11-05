<?php

namespace src\views\components;

final class Paragraph extends Component
{
    public function __construct(
        private readonly array $children,
        private readonly ?string $class = null,
        private readonly ?string $id = null
    ) {
    }

    public function render(): string
    {
        if ($this->class !== null) {
            return '<p class="' . $this->e($this->class) . '">' . $this->renderAll($this->children) . '</p>';
        }

        if ($this->id !== null) {
            return '<p id="' . $this->e($this->id) . '">' . $this->renderAll($this->children) . '</p>';
        }
        
        $html = '<p' . ($this->class !== null ? ' class="' . $this->e($this->class) . '"' : '') . '>';

        return '<p>' . $this->renderAll($this->children) . '</p>';
    }
}
