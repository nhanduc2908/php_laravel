<template>
    <div class="auditor-dashboard"><h1>Audit Dashboard</h1>
        <div class="stats-grid"><div class="stat-card"><div class="stat-icon bg-primary"><i class="fas fa-history"></i></div><div class="stat-info"><h3>{{ stats.total_logs }}</h3><p>Tổng số logs</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-warning"><i class="fas fa-users"></i></div><div class="stat-info"><h3>{{ stats.active_users }}</h3><p>Người dùng hoạt động</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></div><div class="stat-info"><h3>{{ stats.critical_actions }}</h3><p>Hành động quan trọng</p></div></div></div>
        <div class="recent-logs"><h3>Logs gần đây</h3><table class="data-table"><thead><tr><th>Thời gian</th><th>Người dùng</th><th>Hành động</th><th>IP</th></tr></thead><tbody><tr v-for="log in recentLogs" :key="log.id"><td>{{ formatDateTime(log.created_at) }}</td><td>{{ log.user_name }}</td><td>{{ log.action }}</td><td>{{ log.ip }}</td></tr></tbody></table></div>
    </div>
</template>