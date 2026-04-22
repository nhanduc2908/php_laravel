<?php

namespace App\Wrappers;

class FailedWrapper
{
    protected $connection;

    public function __construct()
    {
        $this->connection = ConnectionWrapper::getInstance();
    }

    public function add($job, $exception)
    {
        $query = "INSERT INTO failed_jobs (job, exception, failed_at) VALUES (?, ?, NOW())";
        $stmt = $this->connection->getPdo()->prepare($query);
        $stmt->execute([serialize($job), $exception->getMessage()]);
    }

    public function all()
    {
        $query = "SELECT * FROM failed_jobs ORDER BY failed_at DESC";
        $stmt = $this->connection->getPdo()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function retry($id)
    {
        // Implementation to retry a failed job
    }

    public function forget($id)
    {
        $query = "DELETE FROM failed_jobs WHERE id = ?";
        $stmt = $this->connection->getPdo()->prepare($query);
        $stmt->execute([$id]);
    }
}