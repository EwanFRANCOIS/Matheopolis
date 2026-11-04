<?php

namespace src\views\components;

final class Select extends Component
{
    public function __construct(
        private readonly string $name,
        private readonly string $label,
        private readonly array $children
    ) {
    }

    public function render(): string
    {
        $select = '<label for="' . $this->e($this->name) . '">' . $this->e($this->label) . '</label>';
        $select .= '<select id="' . $this->e($this->name) . '" name="' . $this->e($this->name) . '">';
        foreach ($this->children as $value => $label) {
            if (is_int($value)) {
                $value = $label;
            }
            $select .= '<option value="' . $this->e((string)$value) . '">' . $this->e((string)$label) . '</option>';
        }
        $select .= '</select>';
        return $select;
    }
}
