<?php
namespace Fagathe\Framework\Database\Model\JSON;

use Symfony\Component\Filesystem\Filesystem;

final class DataService
{

    private const DEFAULT_PATH_PREFIX = DOCUMENT_ROOT . '/data/';
    private Filesystem $fs;

    public function __construct(private string $path)
    {
        $this->setPath($path);
        $this->fs = new Filesystem;
    }

    /**
     * @param string $path
     * 
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = self::DEFAULT_PATH_PREFIX . $path . '.json';

        return $this;
    }

    /**
     * @return bool
     */
    public function checkJSONFile(): bool
    {
        if ($this->fs->exists($this->path)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function findAll(): ?array
    {
        if ($this->fs->exists($this->path)) {
            return json_decode(file_get_contents($this->path), true);
        }

        return [];
    }

    /**
     * @param int $id
     * 
     * @return array|null
     */
    public function find(int $id): ?array
    {
        $data = $this->findAll();

        $d = (array) array_reduce($data, fn(array $carry, array $item) => $carry = $item['id'] === $id ? [...$carry, $item] : $carry, []);

        return array_pop($d);
    }

}