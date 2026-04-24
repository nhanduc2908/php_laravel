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
    horizontal: { type: Boolean, default: false },
    options: { type: Object, default: () => ({}) }
})

const chartCanvas = ref(null)
let chartInstance = null

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'top' }, tooltip: { mode: 'index', intersect: false } },
    scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
}

const initChart = () => {
    if (chartInstance) chartInstance.destroy()
    if (chartCanvas.value) {
        chartInstance = new Chart(chartCanvas.value, {
            type: props.horizontal ? 'bar' : 'bar',
            data: { labels: props.labels, datasets: props.datasets },
            options: { indexAxis: props.horizontal ? 'y' : 'x', ...defaultOptions, ...props.options }
        })
    }
}

watch(() => [props.labels, props.datasets, props.horizontal], initChart, { deep: true })
onMounted(initChart)
</script>

<style scoped>
.chart-container { position: relative; width: 100%; height: v-bind('`${height}px`'); }
</style>