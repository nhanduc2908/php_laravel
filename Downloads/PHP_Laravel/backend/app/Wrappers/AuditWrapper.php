<?php

namespace App\Wrappers;

class AuditWrapper
{
    public function log($userId, $action, $resource, $resourceId = null, $old = null, $new = null)
    {
        $query = "INSERT INTO audit_logs (user_id, action, resource, resource_id, old_values, new_values, ip, user_agent, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = ConnectionWrapper::getInstance()->getPdo()->prepare($query);
        $stmt->execute([
            $userId, $action, $resource, $resourceId,
            json_encode($old), json_encode($new),
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
}