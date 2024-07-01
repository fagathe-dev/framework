<?php

namespace Fagathe\Framework\Database;

use Fagathe\Framework\Database\Connection;
use Fagathe\Framework\Helpers\Helpers;

class AbstractModel
{

    protected $table = "posts";
    protected $pdo;
    protected $class = self::class;

    public function __construct()
    {
        $this->pdo = (new Connection())::getConnection();
    }

    /**
     * Get an item from pdo
     * @param integer $int
     * @return null|object|array
     */
    public function find(int $int): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $int]);
        if ($stmt->rowCount() === 1) {
            return $this->class !== "" ? new $this->class($stmt->fetch()) : $stmt->fetch();
        }

        return null;
    }

    /**
     * @param array $criteria
     * @param string|array|null $order_by
     * @param int|null $offset
     * @param int|null $limit
     * 
     * @return mixed
     */
    public function findBy(array $criteria = [], string|array $order_by = null, ?int $offset = null, ?int $limit = null): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->getWhereTables($criteria)}";
        $sqlParts = [];
        if ($order_by !== null) {
            if (is_string($order_by)) {
                $sqlParts[] = 'ORDER BY ' . $order_by;
            }
            if (is_array($order_by)) {
                if (count($order_by) === 1) {
                    $sqlParts[] = 'ORDER BY ' . $order_by[0];
                }
                if (count($order_by) === 2) {
                    $sqlParts[] = 'ORDER BY ' . $order_by[0] . ' ' . strtoupper($order_by[1]);
                }
            }
        }
        if ($offset !== null && is_int($offset)) {
            $sqlParts[] = 'OFFSET ' . $offset;
        }
        if ($limit !== null) {
            $sqlParts[] = 'LIMIT ' . $limit;
        }

        if (count($sqlParts) > 0) {
            $sql .= ' ' . join(" ", $sqlParts);
        }
        dd($sql);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(Helpers::transformKeys($criteria));
        if ($stmt->rowCount() === 1) {
            return $this->class !== "" ? new $this->class($stmt->fetch()) : $stmt->fetch();
        } else if ($stmt->rowCount() > 1) {
            return $this->class !== "" ? $this->getInstances($stmt->fetchAll()) : $stmt->fetchAll();
        } else if ($stmt->rowCount() < 1) {
            return [];
        }
        return NULL;
    }

    /**
     * @param array $criteria
     * @param array|string $field
     * 
     * @return null|object[]|array[]
     */
    public function findOneBy(array $criteria = []): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->getWhereTables($criteria)} LIMIT 1 OFFSET 0;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(Helpers::transformKeys($criteria));
        if ($stmt->rowCount() === 1) {
            return $this->class !== "" ? new $this->class($stmt->fetch()) : $stmt->fetch();
        } else if ($stmt->rowCount() > 1) {
            return $this->class !== "" ? $this->getInstances($stmt->fetchAll()) : $stmt->fetchAll();
        } else if ($stmt->rowCount() < 1) {
            return [];
        }
        return NULL;
    }

    /**
     * findAll
     * @param boolean $order_by
     * @return mixed
     */
    public function findAll($order_by = false)
    {
        $order = "";
        if (is_bool($order_by) && $order_by === true) {
            $order = "ORDER BY created_at DESC";
        } elseif (is_bool($order_by) && $order_by === false) {
            $order = "";
        } elseif (is_string($order_by)) {
            $explode = explode('.', $order_by);
            $order = count($explode) === 1 ? "ORDER BY {$explode[0]}" : "ORDER BY {$explode[0]} {$explode[1]}";
        }
        $sql = "SELECT * FROM {$this->table} {$order}";
        $stmt = $this->pdo->query($sql);
        $data = $stmt->fetchAll();
        if ($this->class !== "") {
            return $this->getInstances($data);
        }
        return $data;
    }

    /**
     * insert
     * @param array $data
     * @param boolean $last_insert
     * @return mixed
     */
    public function insert(array $data, bool $last_insert = false)
    {
        $sql = "INSERT INTO {$this->table} SET {$this->getSetTables($data)}";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute(Helpers::transformKeys($data))) {
            return $last_insert === true ? $this->find((int) $this->pdo->lastInsertId()) : true;
        }
        return false;
    }

    /**
     * update
     * @param array $set
     * @param array $where
     * @param boolean $object
     * @return mixed
     */
    public function update(array $set = [], array $where = [], bool $object = true)
    {
        $sql = "UPDATE {$this->table} SET {$this->getSetTables($set)} ";
        if (count($where) > 0)
            $sql .= "WHERE {$this->getWhereTables($where)}";
        $stmt = $this->pdo->prepare($sql);
        $data = array_merge($set, $where);
        if ($stmt->execute(Helpers::transformKeys($data))) {
            return $object === true ? $this->find((int) $where['id']) : true;
        }
        return false;
    }

    /**
     * delete
     * @param integer $int
     * @return boolean
     */
    public function delete(int $int): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([":id" => $int]) ? true : false;
    }

    /**
     * getSetTables
     * @param array $data
     * @return string
     */
    public function getSetTables(array $data): string
    {
        $set = [];
        foreach ($data as $k => $v) {
            $set[] = "{$k} = :{$k}";
        }
        return join(' , ', $set);
    }

    /**
     * getWhereTables
     * @param array $data
     * @return string
     */
    public function getWhereTables(array $data): string
    {
        $where = [];
        foreach ($data as $k => $v) {
            $where[] = "{$k} = :{$k}";
        }
        if (count($where) === 1) {
            return $where[0];
        }
        if (count($where) === 2) {
            return join(' AND ', $where);
        }
        $str = join(' , ', array_slice($where, 0, -1));
        $last = array_pop($where);

        return join(' AND ', [...$where, $last]);
    }

    /**
     * getInstances
     * @param array $data
     * @return array
     */
    public function getInstances(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = new $this->class($value);
        }
        return $result;
    }

    /**
     * pending
     * @return array
     */
    public function pending(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'pending' ORDER BY created_at DESC";
        $data = $this->pdo->query($sql)->fetchAll();
        return $this->getInstances($data);
    }

}