<?php

namespace src\views\components;

final class Input extends Component
{
    public function __construct(
        private readonly string $name,
        private readonly string $label = '',
        private readonly string $type = 'text',
        private readonly string $placeholder = '',
        private readonly string $value = '',
        private readonly string $id = '',
        private readonly string $style = '',
        private readonly bool $required = true
    ) {
    }

    public function render(): string
    {
        $name = $this->e($this->name);
        $label = $this->e($this->label);
        $type = $this->e($this->type);
        $placeholder = $this->e($this->placeholder);
        $value = $this->e($this->value);
        $style = $this->e($this->style);
        $id = $this->e($this->id);

        $html = '<div';
        if ($this->type === 'checkbox') {
            $html .= ' class="checkbox-container"';
        }

        if ($this->type === 'hidden') {
            $htmlId = $id !== '' ? $id : $name;
            return '<input type="hidden" id="' . $htmlId . '" name="' . $name . '" value="' . $value . '">';
        }

        $html .= '><label for="' . $name . '">' . $label . '</label>';
        $html .= '<input type="' . $type . '"';

        if ($id !== '') {
            $html .= ' id="' . $id . '"';
        }

        $html .= ' name="' . $name . '"';

        if ($this->placeholder !== '') {
            $html .= ' placeholder="' . $placeholder . '"';
        }

        if ($this->value !== '') {
            $html .= ' value="' . $value . '"';
        }

        if ($this->required) {
            $html .= ' required';
        }

        if ($style !== '') {
            $html .= ' style="' . $style . '"';
        }

        $html .= ' class="input"></div>';

        return $html;
    }
}