<?php

namespace App\Wrappers;

class DatabaseLogWrapper
{
    public function write($level, $message, $context = [])
    {
        $query = "INSERT INTO logs (level, message, context, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = ConnectionWrapper::getInstance()->getPdo()->prepare($query);
        $stmt->execute([$level, $message, json_encode($context)]);
    }
}