<?php

namespace modules\views\components;

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
        $select = '<select>';
        foreach ($this->children as $child) {
            $select .= '<option value="' . $this->e($child) . '">' . $this->e($child) . '</option>';
        }
        $select .= '</select>';
        return $select;
    }
}
