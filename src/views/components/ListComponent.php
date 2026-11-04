<?php

namespace src\views\components;

final class ListComponent extends Component
{
    public function __construct(
        private readonly array $items
    ) {
    }

    public function render(): string
    {
        $items = '';
        foreach ($this->items as $item) {
            $items .= '<li>' . $this->e((string)$item) . '</li>';
        }

        return '<ul>' . $items . '</ul>';
    }
}
