<template>
    <div class="chart-container">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const props = defineProps({
    labels: { type: Array, required: true },
    data: { type: Array, required: true },
    colors: { type: Array, default: () => ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'] },
    height: { type: Number, default: 300 },
    options: { type: Object, default: () => ({}) }
})

const chartCanvas = ref(null)
let chartInstance = null

const initChart = () => {
    if (chartInstance) chartInstance.destroy()
    if (chartCanvas.value) {
        chartInstance = new Chart(chartCanvas.value, {
            type: 'pie',
            data: { labels: props.labels, datasets: [{ data: props.data, backgroundColor: props.colors, borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' }, tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} (${((ctx.raw / props.data.reduce((a,b) => a + b, 0)) * 100).toFixed(1)}%)` } } }, ...props.options }
        })
    }
}

watch(() => [props.labels, props.data], initChart, { deep: true })
onMounted(initChart)
</script>

<style scoped>
.chart-container { position: relative; width: 100%; height: v-bind('`${height}px`'); }
</style>