<template>
    <div class="viewer-dashboard"><h1>Dashboard</h1>
        <div class="stats-grid"><div class="stat-card"><div class="stat-icon bg-info"><i class="fas fa-chart-line"></i></div><div class="stat-info"><h3>{{ stats.compliance_rate }}%</h3><p>Tỷ lệ tuân thủ</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></div><div class="stat-info"><h3>{{ stats.open_vulns }}</h3><p>Lỗ hổng tồn tại</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-success"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h3>{{ stats.completed }}</h3><p>Đánh giá hoàn thành</p></div></div></div>
        <div class="recent-reports"><h3>Báo cáo mới nhất</h3><table class="data-table"><thead><tr><th>Máy chủ</th><th>Điểm</th><th>Ngày</th></tr></thead><tbody><tr v-for="r in recentReports" :key="r.id"><td>{{ r.server_name }}</td><td>{{ r.total_score }}%</td><td>{{ formatDate(r.created_at) }}</td></tr></tbody></table></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useDashboardStore } from '@/stores/dashboard'
const dashboardStore = useDashboardStore()
const stats = ref({ compliance_rate: 0, open_vulns: 0, completed: 0 })
const recentReports = ref([])
const formatDate = (date) => new Date(date).toLocaleDateString('vi-VN')
onMounted(async () => { stats.value = await dashboardStore.getViewerStats(); recentReports.value = await dashboardStore.getRecentReports() })
</script>