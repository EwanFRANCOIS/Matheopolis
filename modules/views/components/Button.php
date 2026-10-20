<?php

namespace modules\views\components;

final class Button extends Component
{
    public function __construct(
        private readonly string $label,
        private readonly string $type = 'submit',
        private readonly ?string $name = null,
        private readonly ?string $value = null,
        private readonly ?string $class = null
    ) {
    }

    public function render(): string
    {
        $html = '<button type="' . $this->e($this->type) . '" class="btn';

        if ($this->class !== null) {
            $html .= ' ' . $this->e($this->class);
        }

        $html .= '"';

        if ($this->name !== null) {
            $html .= ' name="' . $this->e($this->name) . '"';
        }

        if ($this->value !== null) {
            $html .= ' value="' . $this->e($this->value) . '"';
        }

        $html .= '>' . $this->e($this->label) . '</button>';

        return $html;
    }
}
