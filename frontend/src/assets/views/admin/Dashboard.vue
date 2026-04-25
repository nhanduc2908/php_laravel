<template>
    <div class="admin-dashboard">
        <div class="page-header"><h1>Admin Dashboard</h1><p>Quản trị hệ thống an ninh</p></div>
        <div class="stats-grid"><div class="stat-card"><div class="stat-icon bg-primary"><i class="fas fa-server"></i></div><div class="stat-info"><h3>{{ stats.servers }}</h3><p>Máy chủ</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-success"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h3>{{ stats.assessments }}</h3><p>Đánh giá</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></div><div class="stat-info"><h3>{{ stats.vulnerabilities }}</h3><p>Lỗ hổng</p></div></div>
        <div class="stat-card"><div class="stat-icon bg-info"><i class="fas fa-users"></i></div><div class="stat-info"><h3>{{ stats.users }}</h3><p>Người dùng</p></div></div></div>
        <div class="charts-row"><div class="chart-card"><h3>Điểm đánh giá theo tháng</h3><canvas ref="scoreChart"></canvas></div><div class="chart-card"><h3>Lỗ hổng theo mức độ</h3><canvas ref="vulnChart"></canvas></div></div>
        <div class="recent-activities"><h3>Hoạt động gần đây</h3><table class="data-table"><thead><tr><th>Thời gian</th><th>Người dùng</th><th>Hành động</th></tr></thead><tbody><tr v-for="act in activities" :key="act.id"><td>{{ formatDate(act.created_at) }}</td><td>{{ act.user_name }}</td><td>{{ act.action }}</td></tr></tbody></table></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useDashboardStore } from '@/stores/dashboard'
import Chart from 'chart.js/auto'
const dashboardStore = useDashboardStore()
const stats = ref({ servers: 0, assessments: 0, vulnerabilities: 0, users: 0 }); const activities = ref([])
const formatDate = (date) => new Date(date).toLocaleString('vi-VN')
onMounted(async () => { stats.value = await dashboardStore.getAdminStats(); activities.value = await dashboardStore.getRecentActivities(); new Chart(document.getElementById('scoreChart'), { type: 'line', data: { labels: ['T1', 'T2', 'T3', 'T4'], datasets: [{ label: 'Điểm TB', data: [65, 70, 75, 82] }] } }); new Chart(document.getElementById('vulnChart'), { type: 'pie', data: { labels: ['Critical', 'High', 'Medium', 'Low'], datasets: [{ data: [5, 12, 8, 3] }] } }) })
</script>