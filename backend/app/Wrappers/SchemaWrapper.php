<?php

namespace App\Wrappers;

class SchemaWrapper
{
    protected $connection;

    public function __construct($connection = null)
    {
        $this->connection = $connection ?: ConnectionWrapper::getInstance();
    }

    public function create($table, $callback)
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $sql = $blueprint->toSql();
        $this->connection->getPdo()->exec($sql);
    }

    public function drop($table)
    {
        $sql = "DROP TABLE IF EXISTS {$table}";
        $this->connection->getPdo()->exec($sql);
    }

    public function hasTable($table)
    {
        $sql = "SHOW TABLES LIKE '{$table}'";
        $stmt = $this->connection->getPdo()->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}

class Blueprint
{
    protected $table;
    protected $columns = [];

    public function __construct($table) { $this->table = $table; }

    public function increments($column) { $this->columns[] = "{$column} INT AUTO_INCREMENT PRIMARY KEY"; }
    public function string($column, $length = 255) { $this->columns[] = "{$column} VARCHAR({$length})"; }
    public function text($column) { $this->columns[] = "{$column} TEXT"; }
    public function integer($column) { $this->columns[] = "{$column} INT"; }
    public function timestamps() { $this->columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"; $this->columns[] = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"; }

    public function toSql()
    {
        $columnsSql = implode(",\n", $this->columns);
        return "CREATE TABLE {$this->table} (\n{$columnsSql}\n)";
    }
}