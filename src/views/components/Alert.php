<?php

namespace src\views\components;

final class Alert extends Component
{
    public function __construct(
        private readonly array $messages,
        private readonly bool $note = false
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

        $alertClass = '';

        if ($this->note) {
            $alertClass .= ' note';
        }

        return '<div class="alert' . $alertClass . '"><ul>' . $items . '</ul></div>';
    }
}
