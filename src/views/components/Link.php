<?php

namespace src\views\components;

final class Link extends Component
{
    public function __construct(
        private readonly string $href,
        private readonly ?string $text,
        private readonly ?string $class = null,
        private readonly ?string $style = null
    ) {
    }

    public function render(): string
    {   
        $html = '<a';

        if ($this->class !== null) {
            $html .= ' class="' . $this->e($this->class) . '"';
        }

        if ($this->style !== null) {
            $html .= ' style="' . $this->e($this->style) . '"';
        }

        $html .= ' href="' . $this->e($this->href) . '"';

        $html .= '>' . $this->e($this->text) . '</a>';

        return $html;
    }
}
