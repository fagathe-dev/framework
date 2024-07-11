<?php
namespace Fagathe\Framework\Router;

final class Route
{
    public function __construct(
        private string $path,
        private string $name,
        private string $action,
        private ?array $methods = null,
        private ?array $requirements = null,
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        if ($this->methods === null) {
            return ['GET'];
        }
        
        return $this->methods;
    }


    /**
     * Get the value of requirements
     */ 
    public function getRequirements()
    {
            return $this->requirements;
    }
}