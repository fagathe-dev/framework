<?php
namespace Fagathe\Framework\Database;

use Fagathe\Framework\Helpers\Helpers;

abstract class AbstractEntity
{

    use Helpers;

    private ?int $id;

    /**
     * __construct
     * @param  mixed $data
     * @return void
     */
    public function __construct(private ?array $data = [])
    {
        $this->__hydrate($data);
    }

    /**
     * _hydrate
     *
     * @param  mixed $data
     *
     * @return static
     */
    public function __hydrate(array $data): self
    {
        foreach ($data as $k => $d):
            $method = 'set' . static::getMethod($k);
            method_exists($this, $method) ? $this->$method($d) : null;
        endforeach;
        return $this;
    }


    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return (int) $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}