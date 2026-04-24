<template>
    <div class="category-card" :class="{ selected: isSelected }" @click="$emit('click', category)">
        <div class="card-icon" :style="{ background: category.color || '#e0e7ff', color: category.textColor || '#4f46e5' }">
            <i :class="category.icon || 'fas fa-folder'"></i>
        </div>
        <div class="card-content">
            <div class="card-header">
                <h4 class="card-title">{{ category.name }}</h4>
                <span class="card-code">{{ category.code }}</span>
            </div>
            <p class="card-description">{{ category.description }}</p>
            <div class="card-footer">
                <span class="card-stats"><i class="fas fa-list"></i> {{ category.criteria_count || 0 }} tiêu chí</span>
                <span class="card-stats" v-if="category.compliance_rate"><i class="fas fa-chart-line"></i> {{ category.compliance_rate }}%</span>
                <span class="card-weight">Trọng số: {{ category.weight || 0 }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({ category: { type: Object, required: true }, selected: { type: Boolean, default: false } })
const emit = defineEmits(['click'])
const isSelected = () => props.selected
</script>

<style scoped>
.category-card {
    display: flex;
    gap: 16px;
    background: var(--card-bg);
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid var(--border-color);
    margin-bottom: 12px;
}
.category-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: var(--primary-300); }
.category-card.selected { border-color: var(--primary-600); background: var(--primary-50); }
.card-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
.card-content { flex: 1; }
.card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px; }
.card-title { margin: 0; font-size: 16px; font-weight: 600; }
.card-code { font-family: monospace; font-size: 11px; padding: 2px 8px; background: var(--bg-secondary); border-radius: 20px; color: var(--text-secondary); }
.card-description { font-size: 13px; color: var(--text-secondary); margin-bottom: 12px; line-height: 1.4; }
.card-footer { display: flex; gap: 16px; font-size: 12px; color: var(--text-secondary); flex-wrap: wrap; }
.card-stats i { margin-right: 4px; }
.card-weight { color: var(--primary-600); font-weight: 500; }
@media (max-width: 640px) { .category-card { flex-direction: column; align-items: center; text-align: center; } .card-footer { justify-content: center; } .card-header { justify-content: center; } }
</style>