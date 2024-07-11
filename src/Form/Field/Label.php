<?php
namespace Fagathe\Framework\Form\Field;

use Fagathe\Framework\Helpers\Helpers;

class Label
{

    use Helpers;

    public function __construct(public string $label, public string $id, public ?array $attributes = null)
    {
    }

    public function getId(): string
    {
        return $this->id ?? strtolower($this->skipAccents($this->getLabel()));
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    private function getAttribute(string $arg): ?string
    {
        return match ($arg) {
            default => null,
        };
    }

    public function render()
    {
        return "<label for=\"{$this->id}\"" . $this->getAttribute('class') . '>' . $this->getLabel() . '</label>';
    }

}