<template>
    <div class="weight-slider">
        <div class="slider-container">
            <input type="range" :value="modelValue" @input="$emit('update:modelValue', parseInt($event.target.value))" :min="min" :max="max" :step="step" class="slider">
            <div class="slider-markers"><span v-for="i in max" :key="i" class="marker" :class="{ active: modelValue >= i }">{{ i }}</span></div>
        </div>
        <div class="slider-value"><span class="value">{{ modelValue }}</span><span class="unit">/ {{ max }}</span></div>
    </div>
</template>

<script setup>
defineProps({ modelValue: { type: Number, required: true }, min: { type: Number, default: 1 }, max: { type: Number, default: 10 }, step: { type: Number, default: 1 } })
defineEmits(['update:modelValue'])
</script>

<style scoped>
.weight-slider { display: flex; align-items: center; gap: 16px; }
.slider-container { flex: 1; }
.slider { width: 100%; height: 6px; -webkit-appearance: none; background: var(--bg-secondary); border-radius: 3px; outline: none; }
.slider::-webkit-slider-thumb { -webkit-appearance: none; width: 18px; height: 18px; border-radius: 50%; background: var(--primary-600); cursor: pointer; border: none; }
.slider::-webkit-slider-thumb:hover { transform: scale(1.2); }
.slider-markers { display: flex; justify-content: space-between; margin-top: 8px; }
.marker { font-size: 10px; color: var(--text-secondary); transition: color 0.2s; }
.marker.active { color: var(--primary-600); font-weight: 600; }
.slider-value { min-width: 60px; text-align: center; }
.value { font-size: 20px; font-weight: 700; color: var(--primary-600); }
.unit { font-size: 12px; color: var(--text-secondary); margin-left: 2px; }
</style>