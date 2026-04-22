<?php

namespace App\Wrappers;

use PDO;

class ConnectionWrapper
{
    protected static $instances = [];
    protected $pdo;

    public static function getInstance($name = 'default')
    {
        if (!isset(self::$instances[$name])) {
            $config = config("database.connections.{$name}");
            self::$instances[$name] = new self($config);
        }
        return self::$instances[$name];
    }

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $config['username'], $config['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPdo() { return $this->pdo; }
}
