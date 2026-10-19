<?php

namespace modules\views\components;

final class Paragraph extends Component
{
    public function __construct(
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
