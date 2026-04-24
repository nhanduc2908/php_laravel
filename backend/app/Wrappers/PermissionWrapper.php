<?php

namespace App\Wrappers;

class PermissionWrapper
{
    protected $auth;

    public function __construct(AuthWrapper $auth)
    {
        $this->auth = $auth;
    }

    public function can($permission)
    {
        $user = $this->auth->user();
        if (!$user) return false;
        
        $roleId = $user['role_id'];
        $query = "SELECT p.slug FROM permissions p 
                  JOIN role_permission rp ON rp.permission_id = p.id 
                  WHERE rp.role_id = ?";
        $stmt = ConnectionWrapper::getInstance()->getPdo()->prepare($query);
        $stmt->execute([$roleId]);
        $permissions = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        return in_array($permission, $permissions);
    }
}