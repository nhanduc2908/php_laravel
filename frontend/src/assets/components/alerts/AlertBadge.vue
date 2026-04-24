<template>
    <div class="alert-badge" :class="severity" @click="$emit('click')">
        <i :class="icon"></i>
        <span class="badge-count" v-if="count > 0">{{ count > 99 ? '99+' : count }}</span>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ severity: { type: String, default: 'info' }, count: { type: Number, default: 0 } })
defineEmits(['click'])

const icon = computed(() => {
    const icons = { critical: 'fas fa-skull-crosswalk', high: 'fas fa-exclamation-triangle', medium: 'fas fa-exclamation-circle', low: 'fas fa-info-circle', info: 'fas fa-bell' }
    return icons[props.severity] || icons.info
})
</script>

<style scoped>
.alert-badge { position: relative; display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; transition: all 0.2s; }
.alert-badge.critical { background: #fee2e2; color: #ef4444; }
.alert-badge.high { background: #ffedd5; color: #f97316; }
.alert-badge.medium { background: #fef3c7; color: #eab308; }
.alert-badge.low { background: #d1fae5; color: #22c55e; }
.alert-badge.info { background: #dbeafe; color: #3b82f6; }
.alert-badge:hover { transform: scale(1.05); }
.badge-count { position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: 10px; font-weight: 600; min-width: 18px; height: 18px; border-radius: 10px; display: flex; align-items: center; justify-content: center; padding: 0 4px; }
</style>