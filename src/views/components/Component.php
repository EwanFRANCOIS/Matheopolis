<?php

namespace src\views\components;

abstract class Component
{
    abstract public function render(): string;

    public function __toString(): string
    {
        return $this->render();
    }

    protected function e(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    protected function renderAll(array $children): string
    {
        $html = '';

        foreach ($children as $child) {
            if ($child instanceof self) {
                $html .= (string) $child;
                continue;
            }

            $html .= $this->e((string) $child);
        }

        return $html;
    }
}
