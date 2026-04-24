<?php

return [
    // Categories
    'categories' => [
        'GOV' => 'System Governance',
        'RISK' => 'System Risk',
        'ASSET' => 'System Assets',
        'ACCESS' => 'Access Control',
        'CRYPTO' => 'System Cryptography',
        'PHYSICAL' => 'Infrastructure',
        'OPERATIONS' => 'System Operations',
        'COMMS' => 'Network & Communications',
        'DEVOPS' => 'DevOps Security',
        'SUPPLY' => 'Supply Chain',
        'INCIDENT' => 'Incident Response',
        'BCP' => 'Backup & Recovery',
        'COMPLIANCE' => 'Technical Compliance',
        'SECURITY_CONFIG' => 'Security Configuration',
        'MONITOR' => 'System Monitoring',
        'THREAT' => 'Threat Management',
        'CLOUD' => 'Cloud Security',
    ],
    
    // Criteria labels
    'criteria' => [
        'GOV-01' => 'System configuration management',
        'GOV-02' => 'Change management process',
        'RISK-01' => 'Vulnerability scanning',
        'RISK-02' => 'CVE assessment',
        'ACCESS-01' => 'Multi-factor authentication (MFA)',
        'ACCESS-02' => 'Role-based access control (RBAC)',
        'CRYPTO-01' => 'AES encryption for data at rest',
        'CRYPTO-02' => 'TLS 1.2+ for data in transit',
        'OPERATIONS-01' => 'Automated backup system',
        'OPERATIONS-02' => 'Log management',
        'MONITOR-01' => 'SIEM implementation',
        'MONITOR-02' => 'Real-time alerting',
        'THREAT-01' => 'Penetration testing',
        'THREAT-02' => 'Threat intelligence',
    ],
    
    'weight' => 'Weight',
    'score' => 'Score',
    'status' => 'Status',
    'compliant' => 'Compliant',
    'non_compliant' => 'Non-Compliant',
    'partial' => 'Partial',
    'not_applicable' => 'N/A',
];