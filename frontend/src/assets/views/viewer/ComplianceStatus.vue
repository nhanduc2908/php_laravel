<template>
    <div class="compliance"><h2>Trạng thái tuân thủ</h2>
        <div class="compliance-summary"><div class="gauge"><canvas ref="gaugeChart"></canvas><h3>{{ overall }}%</h3><p>Tuân thủ tổng thể</p></div></div>
        <table class="data-table"><thead><tr><th>Danh mục</th><th>Điểm</th><th>Trạng thái</th></tr></thead>
        <tbody><tr v-for="cat in categories" :key="cat.id"><td>{{ cat.name }}</td><td>{{ cat.score }}%</td><td><span :class="cat.score >= 70 ? 'badge-success' : 'badge-danger'">{{ cat.score >= 70 ? 'Đạt' : 'Chưa đạt' }}</span></td></tr></tbody>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Chart from 'chart.js/auto'
const overall = ref(0); const categories = ref([])
onMounted(async () => { const res = await fetch('/api/v1/compliance/status'); const data = await res.json(); overall.value = data.overall || 0; categories.value = data.categories || []; new Chart(document.getElementById('gaugeChart'), { type: 'doughnut', data: { datasets: [{ data: [overall.value, 100 - overall.value], backgroundColor: ['#10b981', '#e2e8f0'], borderWidth: 0 }] }, options: { cutout: '70%', plugins: { legend: { display: false } } } }) })
</script>