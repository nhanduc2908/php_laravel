<template>
    <transition name="fade">
        <div v-if="visible" class="error-alert" :class="type">
            <div class="alert-icon">
                <i :class="icon"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">{{ title }}</div>
                <div class="alert-message">{{ message }}</div>
            </div>
            <button class="alert-close" @click="close" v-if="closable">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </transition>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
    title: { type: String, default: 'Lỗi' },
    message: { type: String, required: true },
    type: { type: String, default: 'error', validator: (v) => ['error', 'warning', 'info'].includes(v) },
    closable: { type: Boolean, default: true },
    autoClose: { type: Number, default: 0 }
})

const emit = defineEmits(['update:visible', 'close'])

const icon = computed(() => {
    const icons = { error: 'fas fa-exclamation-circle', warning: 'fas fa-exclamation-triangle', info: 'fas fa-info-circle' }
    return icons[props.type] || 'fas fa-bell'
})

const close = () => {
    emit('update:visible', false)
    emit('close')
}

watch(() => props.visible, (newVal) => {
    if (newVal && props.autoClose > 0) {
        setTimeout(close, props.autoClose)
    }
})
</script>

<style scoped>
.error-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    animation: slideIn 0.3s ease;
}

.error-alert.error {
    background: #fee2e2;
    border-left: 4px solid var(--danger);
}

.error-alert.warning {
    background: #fef3c7;
    border-left: 4px solid var(--warning);
}

.error-alert.info {
    background: #dbeafe;
    border-left: 4px solid var(--info);
}

.alert-icon {
    font-size: 1.25rem;
}

.error-alert.error .alert-icon { color: var(--danger); }
.error-alert.warning .alert-icon { color: var(--warning); }
.error-alert.info .alert-icon { color: var(--info); }

.alert-content { flex: 1; }
.alert-title { font-weight: 600; margin-bottom: 4px; }
.alert-message { font-size: 0.875rem; color: #4a5568; }
.alert-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>