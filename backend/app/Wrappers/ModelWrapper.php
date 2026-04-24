<?php

namespace App\Wrappers;

class ModelWrapper
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $connection;

    public function __construct($table)
    {
        $this->table = $table;
        $this->connection = ConnectionWrapper::getInstance();
    }

    public function find($id)
    {
        $query = new QueryWrapper($this->connection);
        return $query->table($this->table)->where($this->primaryKey, $id)->first();
    }

    public function all()
    {
        $query = new QueryWrapper($this->connection);
        return $query->table($this->table)->get();
    }

    public function create($data)
    {
        $data = array_intersect_key($data, array_flip($this->fillable));
        $columns = implode(',', array_keys($data));
        $values = implode("','", array_values($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ('{$values}')";
        $this->connection->getPdo()->exec($sql);
        return $this->connection->getPdo()->lastInsertId();
    }
}