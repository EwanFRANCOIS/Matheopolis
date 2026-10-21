<?php

namespace src\views\components;

final class Input extends Component
{
    public function __construct(
        private readonly string $name,
        private readonly string $label,
        private readonly string $type = 'text',
        private readonly string $placeholder = '',
        private readonly string $value = '',
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


        $html = '<div';
        if ($this->type === 'checkbox') {
            $html .= ' class="checkbox-container"';
        }
        $html .= '><label for="' . $name . '">' . $label . '</label>';
        $html .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '"';

        if ($this->placeholder !== '') {
            $html .= ' placeholder="' . $placeholder . '"';
        }

        if ($this->value !== '') {
            $html .= ' value="' . $value . '"';
        }

        if ($this->required) {
            $html .= ' required';
        }

        $html .= ' class="input"></div>';

        return $html;
    }
}
