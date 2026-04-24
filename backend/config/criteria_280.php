<?php

$domains = [
    1=>'GOV',2=>'RISK',3=>'ASSET',4=>'ACCESS',5=>'CRYPTO',6=>'PHYSICAL',
    7=>'OPERATIONS',8=>'COMMS',9=>'DEVOPS',10=>'SUPPLY',11=>'INCIDENT',
    12=>'BCP',13=>'COMPLIANCE',14=>'SECURITY_CONFIG',15=>'MONITOR',16=>'THREAT',17=>'CLOUD'
];

// 🔥 Template kỹ thuật (mỗi domain 17 tiêu chí)
$templates = [

'GOV'=>[
'Quản lý cấu hình hệ thống','Quản lý thay đổi hệ thống','Kiểm soát version hệ thống',
'Quản lý policy hệ thống','Audit config hệ thống','Quản lý baseline security',
'Kiểm soát cấu hình server','Quản lý config file','Version control config',
'Kiểm tra config định kỳ','Log thay đổi config','Rollback config',
'Quản lý môi trường hệ thống','Phân tách môi trường','Hardening hệ thống',
'Kiểm tra compliance config','Cải tiến cấu hình'
],

'RISK'=>[
'Quét lỗ hổng hệ thống','Đánh giá CVE','Phân tích risk server','Risk database',
'Risk network','Risk application','Risk cloud','Scan định kỳ',
'Risk scoring','Threat risk','Risk API','Risk container',
'Risk dependency','Risk config','Risk audit','Risk alert','Risk improvement'
],

'ASSET'=>[
'Quản lý server','Quản lý database','Quản lý API','Quản lý container',
'Quản lý VM','Quản lý cloud asset','Inventory hệ thống','Asset tagging',
'Asset monitoring','Asset lifecycle','Asset backup','Asset restore',
'Asset logging','Asset audit','Asset control','Asset risk','Asset security'
],

'ACCESS'=>[
'MFA','RBAC','Session timeout','JWT secure','OAuth secure',
'Password hashing','Login limit','IP restriction','Token expire',
'Admin control','API auth','Secure cookie','CSRF protection',
'Session security','Access log','Privilege control','Auth audit'
],

'CRYPTO'=>[
'AES encryption','TLS 1.2+','Database encryption','Key management',
'Key rotation','Secure storage','Hash password','Encrypt backup',
'SSL config','Certificate validation','Secure API crypto',
'Crypto policy','Key vault','Crypto audit','Secure token',
'Random generator','Crypto improvement'
],

'PHYSICAL'=>[
'Server protection','Rack security','Power backup','Cooling system',
'Access control DC','Camera monitoring','Fire protection',
'Hardware security','Cable protection','Environmental monitoring',
'Physical audit','Device protection','Access logging',
'Visitor control','Physical alert','Infra monitoring','Physical improvement'
],

'OPERATIONS'=>[
'Backup system','Restore system','Log management','Job scheduling',
'Patch management','Config management','Resource monitoring',
'System alert','Error handling','Performance tuning','Downtime control',
'Incident minor','System audit','Log backup','System reporting',
'Ops automation','Ops improvement'
],

'COMMS'=>[
'Firewall','VPN','IDS/IPS','DDoS protection','Rate limit',
'Network segmentation','Secure DNS','API gateway','Port control',
'Load balancing','Traffic monitoring','Proxy security',
'Routing security','Network logging','Zero trust','Network audit','Network improvement'
],

'DEVOPS'=>[
'Secure coding','Dependency scan','CI/CD security','Secrets management',
'Container security','Code review','SAST','DAST','Deploy secure',
'Rollback','Git security','Branch protection','Artifact security',
'Pipeline audit','Build security','Env isolation','DevOps improvement'
],

'SUPPLY'=>[
'Dependency scan','Library audit','Vendor security API','Package integrity',
'Version control lib','Update library','Third-party API security',
'Supply chain scan','Package signature','Dependency alert',
'SBOM','Package monitoring','Vendor patch','Supply audit',
'Risk third-party','Supply logging','Supply improvement'
],

'INCIDENT'=>[
'Incident detection','Incident logging','Incident alert','Auto response',
'Root cause analysis','Incident tracking','Incident dashboard',
'Incident recovery','Incident audit','SIEM alert','Incident escalation',
'Incident timeline','Incident backup','Incident report',
'Incident monitoring','Incident control'
],

'BCP'=>[
'Backup data','Restore data','Disaster recovery','Failover system',
'Multi-region','Backup test','Recovery test','Downtime recovery',
'BCP logging','BCP monitoring','Emergency system','Recovery automation',
'Backup encryption','Backup audit','Recovery audit','BCP improvement'
],

'COMPLIANCE'=>[
'ISO config','Security audit system','Log compliance','Data compliance',
'API compliance','System audit','Security policy system','Audit log',
'Compliance check','Security baseline','Regulation config',
'Compliance monitoring','Compliance reporting','Compliance alert',
'Compliance audit','Compliance improvement'
],

'SECURITY_CONFIG'=>[
'Secure config','Hardening OS','Secure service','Disable unused port',
'Secure env','Secure variable','Config validation','Secret protection',
'Env isolation','Secure deployment','Config audit','Config logging',
'Config monitoring','Config alert','Config control','Config improvement'
],

'MONITOR'=>[
'SIEM','Central log','Real-time alert','Anomaly detection',
'User monitoring','Server monitoring','DB monitoring','API monitoring',
'File integrity','Alert threshold','Dashboard','Event correlation',
'Security alert','Log backup','Monitor audit','Monitor improvement'
],

'THREAT'=>[
'Vuln scan','Pen test','CVE tracking','Threat intel',
'Malware scan','Exploit detect','Patch update','Threat hunting',
'SOC','Threat scoring','Threat alert','Scan automation',
'Zero-day detect','Attack simulation','Threat audit','Threat improvement'
],

'CLOUD'=>[
'Cloud IAM','Security group','Cloud encryption','Cloud logging',
'Cloud monitoring','Cloud backup','Multi-region','Cloud firewall',
'Cloud compliance','Cloud audit','Cloud config','Cloud secret',
'Cloud isolation','Cloud alert','Cloud risk','Cloud scan'
],

];

$criteria = [];
$index = 0;

foreach ($domains as $categoryId => $prefix) {

    $limit = $index < 8 ? 17 : 16;

    for ($i = 1; $i <= $limit; $i++) {

        $criteria[] = [
            'category_id' => $categoryId,
            'code' => $prefix.'-'.str_pad($i,2,'0',STR_PAD_LEFT),
            'name' => $templates[$prefix][$i-1],
            'weight' => $i <= 5 ? 5 : ($i <= 10 ? 4 : ($i <= 15 ? 3 : 2)),
            'standard' => 'ISO 27001',
            'type' => 'technical',
            'status' => true,
        ];
    }

    $index++;
}

return $criteria;