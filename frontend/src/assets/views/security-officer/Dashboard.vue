<template>
    <div class="security-dashboard">
        <div class="page-header"><h1>Security Dashboard</h1><p>Giám sát an ninh hệ thống</p></div>
        <div class="stats-grid"><div class="stat-card"><div class="stat-icon bg-danger"><i class="fas fa-shield-alt"></i></div><div class="stat-info"><h3>{{ stats.critical_vulns }}</h3><p>Lỗ hổng nghiêm trọng</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></div><div class="stat-info"><h3>{{ stats.open_vulns }}</h3><p>Lỗ hổng chưa xử lý</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-success"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h3>{{ stats.fixed_vulns }}</h3><p>Đã xử lý</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-info"><i class="fas fa-chart-line"></i></div><div class="stat-info"><h3>{{ stats.avg_score }}%</h3><p>Điểm TB hệ thống</p></div></div></div>
        <div class="recent-alerts"><h3>Cảnh báo mới nhất</h3><div class="alert-list"><div v-for="alert in recentAlerts" :key="alert.id" :class="['alert-item', alert.severity]"><i class="fas fa-bell"></i> {{ alert.title }} - {{ alert.message }}</div></div></div>
        <div class="pending-assessments"><h3>Đánh giá cần thực hiện</h3><table class="data-table"><thead><tr><th>Máy chủ</th><th>Lần cuối</th><th>Thao tác</th></tr></thead><tbody><tr v-for="server in pendingServers" :key="server.id"><td>{{ server.name }}</td><td>{{ formatDate(server.last_scan) }}</td><td><button @click="runAssessment(server.id)" class="btn-sm">Đánh giá</button></tr></tbody></table></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useSecurityStore } from '@/stores/security'
const securityStore = useSecurityStore()
const stats = ref({ critical_vulns: 0, open_vulns: 0, fixed_vulns: 0, avg_score: 0 })
const recentAlerts = ref([]); const pendingServers = ref([])
const formatDate = (date) => date ? new Date(date).toLocaleDateString('vi-VN') : 'Chưa quét'
const runAssessment = (id) => router.push(`/assessments/run?server=${id}`)
onMounted(async () => { stats.value = await securityStore.getStats(); recentAlerts.value = await securityStore.getRecentAlerts(); pendingServers.value = await securityStore.getPendingServers() })
</script>