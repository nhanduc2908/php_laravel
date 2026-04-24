
<template>
    <div class="test-chart"><div class="chart-header"><h3>Xu hướng kiểm thử</h3><select v-model="period" @change="loadData"><option value="7">7 ngày qua</option><option value="30">30 ngày qua</option><option value="90">90 ngày qua</option></select></div>
    <div class="chart-container"><canvas ref="chartCanvas"></canvas></div>
    <div class="chart-legend"><div class="legend-item"><span class="legend-color passed"></span><span>Đã qua</span></div><div class="legend-item"><span class="legend-color failed"></span><span>Thất bại</span></div><div class="legend-item"><span class="legend-color coverage"></span><span>Coverage</span></div></div>
    <div class="chart-summary"><div class="summary-card"><div class="summary-value">{{ trend.passed_avg }}%</div><div class="summary-label">Tỷ lệ pass trung bình</div></div>
    <div class="summary-card"><div class="summary-value">{{ trend.best_day }}</div><div class="summary-label">Ngày tốt nhất</div></div>
    <div class="summary-card"><div class="summary-value">{{ trend.coverage_trend }}</div><div class="summary-label">Xu hướng coverage</div></div></div></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const period = ref('30')
const chartCanvas = ref(null)
let chartInstance = null
const trend = ref({ passed_avg: 0, best_day: '-', coverage_trend: 'ổn định' })

const loadData = async () => { const res = await fetch(`/api/v1/testing/trend?days=${period.value}`); const data = await res.json(); updateChart(data); updateTrend(data) }
const updateChart = (data) => { if (chartInstance) chartInstance.destroy(); if (chartCanvas.value) { chartInstance = new Chart(chartCanvas.value, { type: 'line', data: { labels: data.dates, datasets: [{ label: 'Tests passed', data: data.passed, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4 }, { label: 'Tests failed', data: data.failed, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', fill: true, tension: 0.4 }, { label: 'Coverage %', data: data.coverage, borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.1)', fill: true, tension: 0.4, yAxisID: 'y1' }] }, options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { tooltip: { callbacks: { label: (ctx) => `${ctx.dataset.label}: ${ctx.raw}${ctx.dataset.label === 'Coverage %' ? '%' : ''}` } } }, scales: { y: { title: { display: true, text: 'Số lượng test' } }, y1: { position: 'right', title: { display: true, text: 'Coverage (%)' }, min: 0, max: 100 } } } }) } }
const updateTrend = (data) => { const totalPassed = data.passed.reduce((a,b) => a + b, 0); const total = data.passed.reduce((a,b,i) => a + b + data.failed[i], 0); trend.value.passed_avg = total > 0 ? Math.round((totalPassed / total) * 100) : 0; const maxPassed = Math.max(...data.passed); const maxIndex = data.passed.indexOf(maxPassed); trend.value.best_day = data.dates[maxIndex] || '-'; const recentAvg = data.coverage.slice(-3).reduce((a,b) => a + b, 0) / 3; const olderAvg = data.coverage.slice(0,3).reduce((a,b) => a + b, 0) / 3; trend.value.coverage_trend = recentAvg > olderAvg ? 'tăng' : (recentAvg < olderAvg ? 'giảm' : 'ổn định') }
onMounted(loadData)
</script>

<style scoped>
.test-chart { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
.chart-header select { padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
.chart-container { height: 350px; margin-bottom: 20px; }
.chart-legend { display: flex; justify-content: center; gap: 24px; margin-bottom: 20px; }
.legend-item { display: flex; align-items: center; gap: 8px; font-size: 13px; }
.legend-color { width: 16px; height: 16px; border-radius: 4px; }
.legend-color.passed { background: #10b981; }
.legend-color.failed { background: #ef4444; }
.legend-color.coverage { background: #6366f1; }
.chart-summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color); }
.summary-card { text-align: center; }
.summary-value { font-size: 24px; font-weight: 700; color: var(--primary-600); margin-bottom: 4px; }
.summary-label { font-size: 12px; color: var(--text-secondary); }
</style>
