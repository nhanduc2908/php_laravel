
<template>
    <div class="stats-card" :class="{ 'clickable': clickable }" @click="clickable && $emit('click')">
        <div class="stats-icon" :style="{ background: iconBg, color: iconColor }">
            <i :class="icon"></i>
        </div>
        <div class="stats-info">
            <div class="stats-value">{{ formatValue(value) }}</div>
            <div class="stats-label">{{ label }}</div>
            <div v-if="trend" class="stats-trend" :class="trendDirection">
                <i :class="trendDirection === 'up' ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                <span>{{ Math.abs(trend) }}% so với tuần trước</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    icon: { type: String, required: true },
    value: { type: [Number, String], required: true },
    label: { type: String, required: true },
    iconBg: { type: String, default: '#e0e7ff' },
    iconColor: { type: String, default: '#4f46e5' },
    trend: { type: Number, default: null },
    clickable: { type: Boolean, default: false }
})

const emit = defineEmits(['click'])

const formatValue = (val) => {
    if (typeof val === 'number') {
        if (val >= 1000000) return (val / 1000000).toFixed(1) + 'M'
        if (val >= 1000) return (val / 1000).toFixed(1) + 'K'
        return val.toLocaleString()
    }
    return val
}

const trendDirection = computed(() => props.trend > 0 ? 'up' : (props.trend < 0 ? 'down' : null))
</script>

<style scoped>
.stats-card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stats-card.clickable {
    cursor: pointer;
}

.stats-card.clickable:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stats-info {
    flex: 1;
}

.stats-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
}

.stats-label {
    font-size: 14px;
    color: var(--text-secondary);
    margin-top: 4px;
}

.stats-trend {
    font-size: 12px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stats-trend.up { color: var(--success); }
.stats-trend.down { color: var(--danger); }
</style>