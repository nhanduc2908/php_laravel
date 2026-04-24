<template>
    <span class="severity-badge" :class="severity">
        <span class="severity-dot"></span>
        {{ label }}
    </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ severity: { type: String, required: true } })

const label = computed(() => {
    const labels = { critical: 'Nghiêm trọng', high: 'Cao', medium: 'Trung bình', low: 'Thấp' }
    return labels[props.severity] || props.severity
})
</script>

<style scoped>
.severity-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.severity-badge.critical { background: #fee2e2; color: #dc2626; }
.severity-badge.high { background: #ffedd5; color: #ea580c; }
.severity-badge.medium { background: #fef3c7; color: #d97706; }
.severity-badge.low { background: #d1fae5; color: #059669; }
.severity-dot { width: 8px; height: 8px; border-radius: 50%; }
.severity-badge.critical .severity-dot { background: #dc2626; }
.severity-badge.high .severity-dot { background: #ea580c; }
.severity-badge.medium .severity-dot { background: #d97706; }
.severity-badge.low .severity-dot { background: #059669; }
</style>