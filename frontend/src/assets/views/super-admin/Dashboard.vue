<template>
    <div class="super-dashboard">
        <div class="page-header"><h1>Super Admin Dashboard</h1><p>Tổng quan toàn hệ thống</p></div>
        <div class="stats-grid"><div v-for="stat in stats" :key="stat.label" class="stat-card"><div class="stat-icon" :class="stat.color"><i :class="stat.icon"></i></div><div class="stat-info"><h3>{{ stat.value }}</h3><p>{{ stat.label }}</p></div></div></div>
        <div class="charts-row"><div class="chart-card"><h3>Xu hướng đánh giá</h3><canvas ref="trendChart"></canvas></div><div class="chart-card"><h3>Lỗ hổng theo mức độ</h3><canvas ref="severityChart"></canvas></div></div>
        <div class="recent-activities"><h3>Hoạt động gần đây</h3><table class="data-table"><thead><tr><th>Thời gian</th><th>Người dùng</th><th>Hành động</th><th>IP</th></tr></thead><tbody><tr v-for="act in activities" :key="act.id"><td>{{ formatDate(act.created_at) }}</td><td>{{ act.user_name }}</td><td>{{ act.action }}</td><td>{{ act.ip }}</td></tr></tbody></table></div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useDashboardStore } from '@/stores/dashboard'
import Chart from 'chart.js/auto'
const dashboardStore = useDashboardStore()
const stats = ref([]); const activities = ref([])
const formatDate = (date) => new Date(date).toLocaleString('vi-VN')
onMounted(async () => { stats.value = await dashboardStore.getSuperAdminStats(); activities.value = await dashboardStore.getRecentActivities(); new Chart(document.getElementById('trendChart'), { type: 'line', data: { labels: ['T1', 'T2', 'T3', 'T4'], datasets: [{ label: 'Điểm TB', data: [65, 70, 75, 82] }] } }); new Chart(document.getElementById('severityChart'), { type: 'pie', data: { labels: ['Critical', 'High', 'Medium', 'Low'], datasets: [{ data: [5, 12, 8, 3] }] } }) })
</script>