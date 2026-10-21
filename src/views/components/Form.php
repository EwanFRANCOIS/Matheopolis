<?php

namespace src\views\components;

final class Form extends Component
{
    public function __construct(
        private readonly string $action,
        private readonly array $children,
        private readonly string $method = 'post',
        private readonly bool $hasFiles = false
    ) {
    }

    public function render(): string
    {
        // Si hasFiles est true, on prépare l'attribut, sinon on laisse vide
        $enctypeAttr = $this->hasFiles ? ' enctype="multipart/form-data"' : '';

        // On injecte l'attribut juste avant le chevron fermant >
        $html = '<form action="' . $this->e($this->action) . '" method="' . $this->e($this->method) . '"' . $enctypeAttr . '>';
        $html .= $this->renderAll($this->children);
        $html .= '</form>';

        return $html;
    }
}