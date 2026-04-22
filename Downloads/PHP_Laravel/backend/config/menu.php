<?php

return [
    'super_admin' => [
        ['title' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'super.dashboard', 'permission' => 'view_dashboard'],
        ['title' => 'Users', 'icon' => 'people', 'route' => 'super.users', 'permission' => 'manage_users'],
        ['title' => 'Roles', 'icon' => 'security', 'route' => 'super.roles', 'permission' => 'manage_roles'],
        ['title' => 'Servers', 'icon' => 'storage', 'route' => 'super.servers', 'permission' => 'manage_servers'],
        ['title' => 'Criteria', 'icon' => 'list', 'route' => 'super.criteria', 'permission' => 'manage_criteria'],
        ['title' => 'Assessments', 'icon' => 'assessment', 'route' => 'super.assessments', 'permission' => 'run_assessments'],
        ['title' => 'Files', 'icon' => 'folder', 'route' => 'super.files', 'permission' => 'manage_files'],
        ['title' => 'Reports', 'icon' => 'description', 'route' => 'super.reports', 'permission' => 'view_reports'],
        ['title' => 'Audit', 'icon' => 'history', 'route' => 'super.audit', 'permission' => 'view_audit'],
        ['title' => 'Settings', 'icon' => 'settings', 'route' => 'super.settings', 'permission' => 'manage_settings'],
    ],
    'admin' => [
        ['title' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'admin.dashboard', 'permission' => 'view_dashboard'],
        ['title' => 'Users', 'icon' => 'people', 'route' => 'admin.users', 'permission' => 'manage_users'],
        ['title' => 'Servers', 'icon' => 'storage', 'route' => 'admin.servers', 'permission' => 'manage_servers'],
        ['title' => 'Criteria', 'icon' => 'list', 'route' => 'admin.criteria', 'permission' => 'manage_criteria'],
        ['title' => 'Assessments', 'icon' => 'assessment', 'route' => 'admin.assessments', 'permission' => 'run_assessments'],
        ['title' => 'Files', 'icon' => 'folder', 'route' => 'admin.files', 'permission' => 'manage_files'],
        ['title' => 'Reports', 'icon' => 'description', 'route' => 'admin.reports', 'permission' => 'view_reports'],
    ],
    'security_officer' => [
        ['title' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'security.dashboard'],
        ['title' => 'Servers', 'icon' => 'storage', 'route' => 'security.servers'],
        ['title' => 'Assessments', 'icon' => 'assessment', 'route' => 'security.assessments'],
        ['title' => 'Files', 'icon' => 'folder', 'route' => 'security.files'],
        ['title' => 'Reports', 'icon' => 'description', 'route' => 'security.reports'],
    ],
    'viewer' => [
        ['title' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'viewer.dashboard'],
        ['title' => 'Reports', 'icon' => 'description', 'route' => 'viewer.reports'],
    ],
    'auditor' => [
        ['title' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'auditor.dashboard'],
        ['title' => 'Audit Logs', 'icon' => 'history', 'route' => 'auditor.audit'],
        ['title' => 'Reports', 'icon' => 'description', 'route' => 'auditor.reports'],
    ],
];