<?php
namespace Fagathe\Framework\Router;

final class Route
{
    public function __construct(
        private string $path,
        private string $name,
        private string $action,
        private ?array $methods = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethods(): array
    {
        if ($this->methods === null) {
            return ['GET'];
        }
        
        return $this->methods;
    }

}