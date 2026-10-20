<?php

namespace src\views\components;

final class Alert extends Component
{
    public function __construct(
        private readonly array $messages
    ) {
    }

    public function render(): string
    {
        if ($this->messages === []) {
            return '';
        }

        $items = '';
        foreach ($this->messages as $message) {
            $items .= '<li>' . $this->e((string) $message) . '</li>';
        }

        return '<div class="alert"><ul>' . $items . '</ul></div>';
    }
}
