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
        $html = '<p';

        if ($this->class !== null) {
           $html .= "class=" . $this->e($this->class) . '"';  
        }

        if ($this->id !== null) {
            $html .= "id=" . $this->e($this->id) . '"';
        }
        
        $html .= '>' . $this->renderAll($this->children) . '</p>';

        return $html;
    }
}
