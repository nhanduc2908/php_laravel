<template>
    <Teleport to="body">
        <TransitionGroup name="toast" tag="div" class="toast-container">
            <div v-for="notification in notifications" :key="notification.id" class="toast" :class="notification.type">
                <div class="toast-icon"><i :class="getIcon(notification.type)"></i></div>
                <div class="toast-content">
                    <div class="toast-title">{{ notification.title }}</div>
                    <div class="toast-message">{{ notification.message }}</div>
                </div>
                <button class="toast-close" @click="removeNotification(notification.id)"><i class="fas fa-times"></i></button>
            </div>
        </TransitionGroup>
    </Teleport>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useWebSocket } from '@/composables/useWebSocket'

const notifications = ref([])
let nextId = 0

const { onEvent, offEvent } = useWebSocket()

const getIcon = (type) => {
    const icons = { success: 'fas fa-check-circle', error: 'fas fa-exclamation-circle', warning: 'fas fa-exclamation-triangle', info: 'fas fa-info-circle', alert: 'fas fa-bell' }
    return icons[type] || icons.info
}

const addNotification = (notification) => {
    const id = nextId++
    notifications.value.push({ id, ...notification, createdAt: Date.now() })
    setTimeout(() => removeNotification(id), 5000)
}

const removeNotification = (id) => {
    notifications.value = notifications.value.filter(n => n.id !== id)
}

const handleNewAlert = (data) => {
    addNotification({
        type: data.severity === 'critical' ? 'error' : 'warning',
        title: data.title,
        message: data.message
    })
}

const handleNewVulnerability = (data) => {
    addNotification({
        type: 'warning',
        title: 'Lỗ hổng mới phát hiện',
        message: `${data.name} (${data.cve || 'N/A'})`
    })
}

const handleAssessmentComplete = (data) => {
    addNotification({
        type: 'success',
        title: 'Đánh giá hoàn tất',
        message: `Máy chủ ${data.server_name} đạt ${data.score}%`
    })
}

const handleFileShared = (data) => {
    addNotification({
        type: 'info',
        title: 'Tệp được chia sẻ',
        message: `${data.shared_by} đã chia sẻ "${data.file_title}" với bạn`
    })
}

onMounted(() => {
    onEvent('alert.new', handleNewAlert)
    onEvent('vulnerability.new', handleNewVulnerability)
    onEvent('assessment.completed', handleAssessmentComplete)
    onEvent('file.shared', handleFileShared)
})

onUnmounted(() => {
    offEvent('alert.new', handleNewAlert)
    offEvent('vulnerability.new', handleNewVulnerability)
    offEvent('assessment.completed', handleAssessmentComplete)
    offEvent('file.shared', handleFileShared)
})

defineExpose({ addNotification })
</script>

<style scoped>
.toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 1100; display: flex; flex-direction: column; gap: 10px; }
.toast { display: flex; align-items: center; gap: 12px; background: white; border-radius: 10px; padding: 12px 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 300px; max-width: 400px; border-left: 4px solid; }
.toast.success { border-left-color: #10b981; } .toast.success .toast-icon { color: #10b981; }
.toast.error { border-left-color: #ef4444; } .toast.error .toast-icon { color: #ef4444; }
.toast.warning { border-left-color: #f59e0b; } .toast.warning .toast-icon { color: #f59e0b; }
.toast.info { border-left-color: #3b82f6; } .toast.info .toast-icon { color: #3b82f6; }
.toast-content { flex: 1; }
.toast-title { font-weight: 600; margin-bottom: 4px; font-size: 14px; }
.toast-message { font-size: 13px; color: #666; }
.toast-close { background: none; border: none; cursor: pointer; color: #999; padding: 4px; }
.toast-close:hover { color: #666; }
.toast-enter-active, .toast-leave-active { transition: all 0.3s; }
.toast-enter-from { transform: translateX(100%); opacity: 0; }
.toast-leave-to { transform: translateX(100%); opacity: 0; }
@media (max-width: 640px) { .toast-container { left: 20px; right: 20px; } .toast { min-width: auto; width: 100%; } }
</style>