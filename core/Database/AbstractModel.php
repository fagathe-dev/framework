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
        if( $stmt->rowCount() === 1 ) {               
            return $this->class !== "" ? new $this->class($stmt->fetch()) : $stmt->fetch();
        }

        return null;
    }

    /**
     * Get items from pdo
    * @param integer $int
    * @return mixed
    */
    public function findBy(string $args = "")
    {
        $field = explode('.', $args)[0];
        $value = explode('.', $args)[1];
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :{$field}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":{$field}" => $value]);
        if( $stmt->rowCount() === 1 ) {               
            return $this->class !== "" ? new $this->class($stmt->fetch()) : $stmt->fetch();
        } else if ( $stmt->rowCount() > 1 ) {
            return $this->class !== "" ? $this->getInstances($stmt->fetchAll(), $this->class) : $stmt->fetchAll();
        } else if ( $stmt->rowCount() < 1 ) {
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
        if ( $this->class !== "" ) {
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
        if ( $stmt->execute( Helpers::transformKeys($data) )  ){
            return $last_insert === true ? $this->find((int) $this->pdo->lastInsertId(), $this->class) : true;
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
    public function update(array $set = [], array $where = [], bool $object = false) 
    {
        $sql = "UPDATE {$this->table} SET {$this->getSetTables($set)} ";  
        if ( count($where) > 0 ) $sql .= "WHERE {$this->getWhereTables($where)}"; 
        $stmt = $this->pdo->prepare($sql); 
        $data = array_merge($set, $where);
        if ( $stmt->execute(Helpers::transformKeys($data)) ) {
            return $object === true ? $this->find((int) $where['id'], $this->class) : true;
        }
        return false;
    }

    /**
     * delete
    * @param integer $int
    * @return boolean
    */
    public function delete(int $int):bool
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
        foreach ($data as $k => $v){
            $set[] = "{$k} = :{$k}";
        }
        return join(' , ', $set);
    }

    /**
     * getWhereTables
    * @param array $data
    * @return string
    */
    public function getWhereTables(array $data):string
    {          
        $where = [];
        foreach ($data as $k => $v){
            $where[] = "{$k} = :{$k}";
        }
        return join(' AND ', $where);
    }

    /**
     * getInstances
    * @param array $data
    * @return array
    */
    public function getInstances(array $data):array
    {
        $result = [];
        foreach ( $data as $key => $value ) {
            $result[$key] = new $this->class($value); 
        }
        return $result;
    }
    
    /**
     * pending
    * @return array
    */
    public function pending():array 
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'pending' ORDER BY created_at DESC";
        $data = $this->pdo->query($sql)->fetchAll();
        return $this->getInstances($data);
    }

}