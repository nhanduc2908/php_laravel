<template>
    <div class="super-admin-dashboard">
        <div class="page-header">
            <h1>Super Admin Dashboard</h1>
            <p>Tổng quan hệ thống an ninh</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card" v-for="stat in stats" :key="stat.label">
                <div class="stat-icon" :class="stat.color">
                    <i :class="stat.icon"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ stat.value }}</h3>
                    <p>{{ stat.label }}</p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-row">
            <div class="chart-card">
                <h3>Xu hướng đánh giá</h3>
                <canvas ref="trendChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Lỗ hổng theo mức độ</h3>
                <canvas ref="severityChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="recent-activities">
            <h3>Hoạt động gần đây</h3>
            <table class="data-table">
                <thead>
                    <tr><th>Thời gian</th><th>Người dùng</th><th>Hành động</th><th>Chi tiết</th></tr>
                </thead>
                <tbody>
                    <tr v-for="activity in recentActivities" :key="activity.id">
                        <td>{{ formatDate(activity.created_at) }}</td>
                        <td>{{ activity.user_name }}</td>
                        <td>{{ activity.action }}</td>
                        <td>{{ activity.details }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useDashboardStore } from '@/stores/dashboard'
import Chart from 'chart.js/auto'

const dashboardStore = useDashboardStore()
const stats = ref([])
const recentActivities = ref([])

const formatDate = (date) => new Date(date).toLocaleString('vi-VN')

onMounted(async () => {
    stats.value = await dashboardStore.getSuperAdminStats()
    recentActivities.value = await dashboardStore.getRecentActivities()
    
    // Initialize charts
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: { labels: ['T1', 'T2', 'T3', 'T4'], datasets: [{ label: 'Điểm TB', data: [65, 70, 75, 82] }] }
    })
    new Chart(document.getElementById('severityChart'), {
        type: 'pie',
        data: { labels: ['Critical', 'High', 'Medium', 'Low'], datasets: [{ data: [5, 12, 8, 3] }] }
    })
})
</script>