<template>
    <Teleport to="body">
        <transition-group name="toast" tag="div" class="toast-container">
            <div v-for="toast in toasts" :key="toast.id" class="toast" :class="toast.type">
                <div class="toast-icon"><i :class="getIcon(toast.type)"></i></div>
                <div class="toast-content"><div class="toast-title">{{ toast.title }}</div><div class="toast-message">{{ toast.message }}</div></div>
                <button class="toast-close" @click="removeToast(toast.id)"><i class="fas fa-times"></i></button>
            </div>
        </transition-group>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue'

const toasts = ref([])
let nextId = 0

const getIcon = (type) => ({ success: 'fas fa-check-circle', error: 'fas fa-exclamation-circle', warning: 'fas fa-exclamation-triangle', info: 'fas fa-info-circle' }[type] || 'fas fa-bell')

const addToast = (message, type = 'success', title = '', duration = 3000) => {
    const id = nextId++
    const titles = { success: 'Thành công', error: 'Lỗi', warning: 'Cảnh báo', info: 'Thông báo' }
    toasts.value.push({ id, title: title || titles[type], message, type })
    setTimeout(() => removeToast(id), duration)
}

const removeToast = (id) => { toasts.value = toasts.value.filter(t => t.id !== id) }

defineExpose({ success: (msg, title) => addToast(msg, 'success', title), error: (msg, title) => addToast(msg, 'error', title), warning: (msg, title) => addToast(msg, 'warning', title), info: (msg, title) => addToast(msg, 'info', title) })
</script>

<style scoped>
.toast-container { position: fixed; top: 20px; right: 20px; z-index: 1100; display: flex; flex-direction: column; gap: 10px; }
.toast { display: flex; align-items: center; gap: 12px; background: white; border-radius: 8px; padding: 12px 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 300px; animation: slideIn 0.3s ease; border-left: 4px solid; }
.toast.success { border-left-color: #10b981; } .toast.success .toast-icon { color: #10b981; }
.toast.error { border-left-color: #ef4444; } .toast.error .toast-icon { color: #ef4444; }
.toast.warning { border-left-color: #f59e0b; } .toast.warning .toast-icon { color: #f59e0b; }
.toast.info { border-left-color: #3b82f6; } .toast.info .toast-icon { color: #3b82f6; }
.toast-content { flex: 1; }
.toast-title { font-weight: 600; margin-bottom: 4px; }
.toast-message { font-size: 0.875rem; color: #666; }
.toast-close { background: none; border: none; cursor: pointer; color: #999; }
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
.toast-enter-active, .toast-leave-active { transition: all 0.3s; }
.toast-enter-from { transform: translateX(100%); opacity: 0; }
.toast-leave-to { transform: translateX(100%); opacity: 0; }
</style>