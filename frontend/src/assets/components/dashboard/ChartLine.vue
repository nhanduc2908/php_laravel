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
    datasets: { type: Array, required: true },
    height: { type: Number, default: 300 },
    options: { type: Object, default: () => ({}) }
})

const chartCanvas = ref(null)
let chartInstance = null

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'top', labels: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary') } },
        tooltip: { mode: 'index', intersect: false }
    },
    scales: {
        y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary') } },
        x: { ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary') } }
    }
}

const initChart = () => {
    if (chartInstance) chartInstance.destroy()
    if (chartCanvas.value) {
        chartInstance = new Chart(chartCanvas.value, {
            type: 'line',
            data: { labels: props.labels, datasets: props.datasets },
            options: { ...defaultOptions, ...props.options, responsive: true, maintainAspectRatio: false }
        })
    }
}

watch(() => [props.labels, props.datasets], initChart, { deep: true })
onMounted(initChart)
</script>

<style scoped>
.chart-container { position: relative; width: 100%; height: v-bind('`${height}px`'); }
</style>