<?php

namespace src\views\components;

final class Tile extends Component
{
    public function __construct(
        private readonly ?string $href,
        private readonly string $symbol,
        private readonly string $title,
        private readonly string $description,
        private readonly ?string $class = null,
        private readonly ?string $id = null,
        private readonly string $type = 'button'
    ) {
    }

    public function render(): string
    {
        $tag = $this->href === null ? 'button' : 'a';
        $html = '<' . $tag;

        if ($this->class !== null) {
            $html .= ' class="tile ' . $this->e($this->class) . '"';
        } else {
            $html .= ' class="tile"';
        }

        if ($this->href !== null) {
            $html .= ' href="' . $this->e($this->href) . '"';
        } else {
            $html .= ' type="' . $this->e($this->type) . '"';
        }

        if ($this->id !== null) {
            $html .= ' id="' . $this->e($this->id) . '"';
        }

        $html .= '>';
        $html .= '<span class="tile-symbol">' . $this->e($this->symbol) . '</span>';
        $html .= '<span class="tile-title">' . $this->e($this->title) . '</span>';
        $html .= '<span class="tile-desc">' . $this->e($this->description) . '</span>';
        $html .= '</' . $tag . '>';

        return $html;
    }
}
