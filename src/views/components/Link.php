<?php

namespace src\views\components;

final class Link extends Component
{
    public function __construct(
        private readonly string $href,
        private readonly string $text,
        private readonly string $class
    ) {
    }

    public function render(): string
    {   
        $html = '<a ';

        if ($this->class !== null) {
            $html .= 'class="' . $this->e($this->class) . '"';
        }

        if ($this->href !== null) {
            $html .= ' href="' . $this->e($this->href) . '"';
        }

        if ($this->text !== null) {
            $html .= $this->e($this->text);
        }

        $html .= '>' . $this->e($this->text) . '</a>';

        return $html;
    }
}
