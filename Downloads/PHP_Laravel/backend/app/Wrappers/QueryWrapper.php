<?php

namespace App\Wrappers;

class QueryWrapper
{
    protected $connection;
    protected $query;

    public function __construct($connection = null)
    {
        $this->connection = $connection ?: ConnectionWrapper::getInstance();
        $this->query = '';
    }

    public function table($table)
    {
        $this->query = "SELECT * FROM {$table}";
        return $this;
    }

    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        $this->query .= " WHERE {$column} {$operator} '{$value}'";
        return $this;
    }

    public function get()
    {
        $stmt = $this->connection->getPdo()->prepare($this->query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function first()
    {
        $stmt = $this->connection->getPdo()->prepare($this->query . ' LIMIT 1');
        $stmt->execute();
        return $stmt->fetch();
    }
}