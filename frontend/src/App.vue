<template>
    <div id="app" :class="themeClass">
        <!-- Router View - Hiển thị trang hiện tại -->
        <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
                <component :is="Component" />
            </transition>
        </router-view>
        
        <!-- Global Loading Overlay -->
        <loading v-model:active="isLoading" 
                 :can-cancel="false"
                 :is-full-page="true" />
        
        <!-- Global Modal Container -->
        <Teleport to="body">
            <div id="global-modal"></div>
        </Teleport>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import { useNotificationStore } from '@/stores/notification'
import { useWebSocket } from '@/composables/useWebSocket'

// Stores
const authStore = useAuthStore()
const themeStore = useThemeStore()
const notificationStore = useNotificationStore()
const router = useRouter()
const route = useRoute()

// WebSocket connection
const { connect, disconnect, onEvent } = useWebSocket()

// Computed
const themeClass = computed(() => {
    return themeStore.isDark ? 'dark-theme' : 'light-theme'
})

const isLoading = computed(() => {
    return notificationStore.globalLoading
})

// Methods
const initApp = async () => {
    // Check authentication
    if (authStore.token) {
        try {
            await authStore.fetchCurrentUser()
            connect(authStore.token)
            setupWebSocketListeners()
        } catch (error) {
            console.error('Failed to fetch user:', error)
            authStore.logout()
        }
    }
    
    // Load theme from localStorage
    themeStore.loadTheme()
    
    // Start notification polling
    notificationStore.startPolling()
}

const setupWebSocketListeners = () => {
    // New alert event
    onEvent('alert.new', (data) => {
        notificationStore.addNotification({
            type: 'alert',
            severity: data.severity,
            title: data.title,
            message: data.message
        })
    })
    
    // Scan progress event
    onEvent('scan.progress', (data) => {
        notificationStore.updateScanProgress(data.server_id, data.progress)
    })
    
    // Assessment completed event
    onEvent('assessment.completed', (data) => {
        notificationStore.addNotification({
            type: 'success',
            title: 'Assessment Completed',
            message: `Assessment for server ${data.server_name} completed with score ${data.score}%`
        })
    })
    
    // File shared event
    onEvent('file.shared', (data) => {
        notificationStore.addNotification({
            type: 'info',
            title: 'File Shared',
            message: `${data.shared_by} shared "${data.file_title}" with you`
        })
    })
    
    // New vulnerability event
    onEvent('vulnerability.new', (data) => {
        notificationStore.addNotification({
            type: 'warning',
            severity: data.severity,
            title: 'New Vulnerability Found',
            message: `${data.name} (${data.cve})`
        })
    })
}

// Handle before unload
const handleBeforeUnload = () => {
    disconnect()
}

// Watch route changes for page title
watch(() => route.meta.title, (title) => {
    document.title = title ? `${title} | Security Assessment Platform` : 'Security Assessment Platform'
}, { immediate: true })

// Lifecycle hooks
onMounted(() => {
    initApp()
    window.addEventListener('beforeunload', handleBeforeUnload)
})

onUnmounted(() => {
    disconnect()
    notificationStore.stopPolling()
    window.removeEventListener('beforeunload', handleBeforeUnload)
})
</script>

<style lang="scss">
/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

#app {
    height: 100%;
    min-height: 100vh;
}

/* Theme Variables */
.light-theme {
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --bg-sidebar: #ffffff;
    --text-primary: #1a1a2e;
    --text-secondary: #4a5568;
    --border-color: #e2e8f0;
    --card-bg: #ffffff;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.dark-theme {
    --bg-primary: #1a1a2e;
    --bg-secondary: #16213e;
    --bg-sidebar: #0f3460;
    --text-primary: #ffffff;
    --text-secondary: #a0aec0;
    --border-color: #2d3748;
    --card-bg: #1e293b;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Scrollbar Styles */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--bg-secondary);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Loading Animation */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--border-color);
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

/* Toast Customization */
.v-toast__item {
    border-radius: 8px !important;
    font-family: inherit !important;
}

.v-toast__item--success {
    background-color: #10b981 !important;
}

.v-toast__item--error {
    background-color: #ef4444 !important;
}

.v-toast__item--warning {
    background-color: #f59e0b !important;
}

.v-toast__item--info {
    background-color: #3b82f6 !important;
}
</style>