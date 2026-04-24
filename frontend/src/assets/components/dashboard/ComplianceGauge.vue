<template>
    <div class="compliance-gauge">
        <div class="gauge-header">
            <h3>Mức độ tuân thủ</h3>
            <div class="gauge-value">{{ complianceRate }}%</div>
        </div>
        <div class="gauge-container">
            <div class="gauge-progress" :style="{ width: `${complianceRate}%` }"></div>
        </div>
        <div class="gauge-stats">
            <div class="stat"><span class="stat-label">Đã đạt:</span><span class="stat-value">{{ compliantCount }}</span></div>
            <div class="stat"><span class="stat-label">Chưa đạt:</span><span class="stat-value">{{ nonCompliantCount }}</span></div>
            <div class="stat"><span class="stat-label">Không áp dụng:</span><span class="stat-value">{{ naCount }}</span></div>
        </div>
        <div class="gauge-footer">
            <span class="status" :class="statusClass">{{ statusText }}</span>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const complianceRate = ref(0)
const compliantCount = ref(0)
const nonCompliantCount = ref(0)
const naCount = ref(0)

const statusClass = computed(() => {
    if (complianceRate.value >= 80) return 'excellent'
    if (complianceRate.value >= 60) return 'good'
    if (complianceRate.value >= 40) return 'average'
    return 'poor'
})

const statusText = computed(() => {
    if (complianceRate.value >= 80) return 'Tốt'
    if (complianceRate.value >= 60) return 'Khá'
    if (complianceRate.value >= 40) return 'Trung bình'
    return 'Yếu'
})

onMounted(async () => {
    const response = await fetch('/api/v1/dashboard/compliance')
    const data = await response.json()
    complianceRate.value = data.compliance_rate || 0
    compliantCount.value = data.compliant_count || 0
    nonCompliantCount.value = data.non_compliant_count || 0
    naCount.value = data.na_count || 0
})
</script>

<style scoped>
.compliance-gauge { background: var(--card-bg); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); }
.gauge-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 16px; }
.gauge-header h3 { margin: 0; }
.gauge-value { font-size: 28px; font-weight: 700; color: var(--primary-600); }
.gauge-container { background: var(--bg-secondary); border-radius: 10px; height: 12px; overflow: hidden; margin-bottom: 20px; }
.gauge-progress { background: linear-gradient(90deg, var(--primary-600), var(--primary-400)); height: 100%; border-radius: 10px; transition: width 0.5s ease; }
.gauge-stats { display: flex; justify-content: space-between; margin-bottom: 16px; }
.stat { text-align: center; }
.stat-label { font-size: 12px; color: var(--text-secondary); display: block; margin-bottom: 4px; }
.stat-value { font-size: 18px; font-weight: 600; }
.gauge-footer { text-align: center; }
.status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.status.excellent { background: #d1fae5; color: #10b981; }
.status.good { background: #dbeafe; color: #3b82f6; }
.status.average { background: #fef3c7; color: #f59e0b; }
.status.poor { background: #fee2e2; color: #ef4444; }
</style>