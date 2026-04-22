<?php

namespace App\Wrappers;

class TransactionWrapper
{
    protected $connection;

    public function __construct($connection = null)
    {
        $this->connection = $connection ?: ConnectionWrapper::getInstance();
    }

    public function begin()
    {
        $this->connection->getPdo()->beginTransaction();
        return $this;
    }

    public function commit()
    {
        $this->connection->getPdo()->commit();
        return $this;
    }

    public function rollback()
    {
        $this->connection->getPdo()->rollBack();
        return $this;
    }

    public function execute($callback)
    {
        try {
            $this->begin();
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}