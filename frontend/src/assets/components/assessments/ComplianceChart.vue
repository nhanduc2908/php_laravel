<template>
    <div class="compliance-chart">
        <div class="chart-header"><h3>Biểu đồ tuân thủ theo danh mục</h3></div>
        <div class="chart-container"><canvas ref="chartCanvas"></canvas></div>
        <div class="chart-legend"><div v-for="item in data" :key="item.name" class="legend-item"><span class="legend-color" :style="{ background: item.color }"></span><span>{{ item.name }}</span><span class="legend-value">{{ item.value }}%</span></div></div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const props = defineProps({ data: { type: Array, required: true } })
const chartCanvas = ref(null)
let chartInstance = null

const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16', '#14b8a6', '#f97316']

const initChart = () => {
    if (chartInstance) chartInstance.destroy()
    if (chartCanvas.value && props.data.length) {
        const chartData = props.data.map((item, i) => ({ ...item, color: colors[i % colors.length] }))
        chartInstance = new Chart(chartCanvas.value, { type: 'bar', data: { labels: chartData.map(d => d.name), datasets: [{ label: 'Tỷ lệ tuân thủ (%)', data: chartData.map(d => d.value), backgroundColor: chartData.map(d => d.color), borderRadius: 8 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100, title: { display: true, text: 'Phần trăm (%)' } } }, plugins: { tooltip: { callbacks: { label: (ctx) => `${ctx.raw}%` } } } } })
    }
}

watch(() => props.data, initChart, { deep: true })
onMounted(initChart)
</script>

<style scoped>
.compliance-chart { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.chart-header { margin-bottom: 20px; }
.chart-container { height: 300px; margin-bottom: 20px; }
.chart-legend { display: flex; flex-wrap: wrap; gap: 16px; justify-content: center; }
.legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; }
.legend-color { width: 12px; height: 12px; border-radius: 3px; }
.legend-value { font-weight: 600; margin-left: 4px; }
</style>