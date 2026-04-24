<template>
    <footer class="app-footer">
        <div class="footer-content">
            <div class="footer-left">
                <span>&copy; {{ currentYear }} {{ appName }}. All rights reserved.</span>
            </div>
            <div class="footer-center">
                <span class="status-indicator" :class="{ online: isServerOnline }">
                    <i class="fas fa-circle"></i>
                    {{ isServerOnline ? 'Server Online' : 'Server Offline' }}
                </span>
            </div>
            <div class="footer-right">
                <a href="#" @click.prevent="openPrivacyPolicy">Privacy Policy</a>
                <span class="separator">|</span>
                <a href="#" @click.prevent="openTermsOfService">Terms of Service</a>
                <span class="separator">|</span>
                <a href="#" @click.prevent="openContact">Contact</a>
            </div>
        </div>
        
        <!-- Server Status Details -->
        <div class="server-status" v-if="showServerStatus">
            <div class="status-item">
                <span>Response Time:</span>
                <span :class="{ high: responseTime > 500 }">{{ responseTime }}ms</span>
            </div>
            <div class="status-item">
                <span>Uptime:</span>
                <span>{{ uptime }}</span>
            </div>
            <div class="status-item">
                <span>Last Check:</span>
                <span>{{ lastCheckTime }}</span>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const appName = ref(import.meta.env.VITE_APP_NAME || 'Security Assessment Platform')
const currentYear = computed(() => new Date().getFullYear())
const isServerOnline = ref(true)
const responseTime = ref(0)
const uptime = ref('0d 0h 0m')
const lastCheckTime = ref('')
const showServerStatus = ref(false)

let healthCheckInterval = null

const checkServerHealth = async () => {
    const start = Date.now()
    try {
        const response = await fetch('/api/v1/health')
        const end = Date.now()
        isServerOnline.value = response.ok
        responseTime.value = end - start
        lastCheckTime.value = new Date().toLocaleTimeString()
    } catch (error) {
        isServerOnline.value = false
        responseTime.value = 0
    }
}

const openPrivacyPolicy = () => {
    window.open('/privacy-policy', '_blank')
}

const openTermsOfService = () => {
    window.open('/terms-of-service', '_blank')
}

const openContact = () => {
    window.location.href = 'mailto:support@security-platform.com'
}

onMounted(() => {
    checkServerHealth()
    healthCheckInterval = setInterval(checkServerHealth, 30000) // Check every 30 seconds
    
    // Show server status on hover
    const footer = document.querySelector('.app-footer')
    if (footer) {
        footer.addEventListener('mouseenter', () => {
            showServerStatus.value = true
        })
        footer.addEventListener('mouseleave', () => {
            showServerStatus.value = false
        })
    }
})

onUnmounted(() => {
    if (healthCheckInterval) {
        clearInterval(healthCheckInterval)
    }
})
</script>

<style scoped>
.app-footer {
    background: var(--bg-secondary);
    border-top: 1px solid var(--border-color);
    padding: 12px 24px;
    font-size: 0.75rem;
    color: var(--text-secondary);
    position: relative;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.footer-left,
.footer-center,
.footer-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
}

.status-indicator i {
    font-size: 0.6rem;
}

.status-indicator.online i {
    color: var(--success);
}

.status-indicator.offline i {
    color: var(--danger);
}

.footer-right a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.2s;
}

.footer-right a:hover {
    color: var(--primary-600);
}

.separator {
    color: var(--border-color);
}

.server-status {
    position: absolute;
    bottom: 100%;
    right: 20px;
    background: var(--card-bg);
    border-radius: 8px;
    padding: 12px 16px;
    box-shadow: var(--shadow-lg);
    margin-bottom: 8px;
    min-width: 200px;
}

.status-item {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    padding: 4px 0;
    font-size: 0.7rem;
}

.status-item .high {
    color: var(--danger);
}

/* Responsive */
@media (max-width: 768px) {
    .app-footer {
        padding: 12px 16px;
    }
    
    .footer-content {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-right {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>