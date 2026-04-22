<?php

return [
    'roles' => [
        'super_admin' => [
            'id' => 1,
            'name' => 'Super Admin',
            'level' => 100,
            'permissions' => ['*'],
        ],
        'admin' => [
            'id' => 2,
            'name' => 'Admin',
            'level' => 80,
            'permissions' => ['view_users', 'create_users', 'edit_users', 'delete_users', 'view_servers', 'manage_servers', 'view_reports', 'manage_files', 'view_audit'],
        ],
        'security_officer' => [
            'id' => 3,
            'name' => 'Security Officer',
            'level' => 60,
            'permissions' => ['view_servers', 'run_assessments', 'view_reports', 'manage_files'],
        ],
        'viewer' => [
            'id' => 4,
            'name' => 'Viewer',
            'level' => 40,
            'permissions' => ['view_reports'],
        ],
        'auditor' => [
            'id' => 5,
            'name' => 'Auditor',
            'level' => 50,
            'permissions' => ['view_audit', 'view_reports'],
        ],
    ],
    'default_role' => 'viewer',
];