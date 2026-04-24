<template>
    <div class="session-list">
        <div class="list-header"><h3>Phiên đăng nhập hiện tại</h3><button class="btn btn-danger btn-sm" @click="terminateAll" :disabled="terminating"><i v-if="terminating" class="fas fa-spinner fa-spin"></i> Kết thúc tất cả phiên</button></div>
        <div class="sessions-grid"><div v-for="session in sessions" :key="session.id" class="session-card" :class="{ current: session.is_current }"><div class="session-device"><i :class="getDeviceIcon(session.device_type)"></i><span>{{ session.device_name || session.device_type }}</span></div>
        <div class="session-info"><div class="info-row"><span class="label">IP:</span><span class="value">{{ session.ip }}</span></div><div class="info-row"><span class="label">Trình duyệt:</span><span class="value">{{ session.browser }}</span></div><div class="info-row"><span class="label">Hệ điều hành:</span><span class="value">{{ session.os }}</span></div><div class="info-row"><span class="label">Đăng nhập lúc:</span><span class="value">{{ formatDateTime(session.created_at) }}</span></div><div class="info-row"><span class="label">Hoạt động lần cuối:</span><span class="value">{{ formatDateTime(session.last_activity) }}</span></div></div>
        <div class="session-actions"><button v-if="!session.is_current" class="btn btn-sm btn-outline" @click="terminate(session.id)">Kết thúc phiên</button><span v-else class="current-badge">Phiên hiện tại</span></div></div></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()
const sessions = ref([])
const terminating = ref(false)

const loadSessions = async () => { const res = await fetch('/api/v1/profile/sessions'); sessions.value = (await res.json()).data || [] }
const getDeviceIcon = (type) => ({ desktop: 'fas fa-desktop', laptop: 'fas fa-laptop', mobile: 'fas fa-mobile-alt', tablet: 'fas fa-tablet-alt' }[type] || 'fas fa-question-circle')
const formatDateTime = (date) => date ? new Date(date).toLocaleString('vi-VN') : 'N/A'
const terminate = async (id) => { await fetch(`/api/v1/profile/sessions/${id}/terminate`, { method: 'DELETE' }); loadSessions(); toast.success('Đã kết thúc phiên đăng nhập') }
const terminateAll = async () => { terminating.value = true; await fetch('/api/v1/profile/sessions/terminate-all', { method: 'DELETE' }); loadSessions(); toast.success('Đã kết thúc tất cả phiên đăng nhập khác'); terminating.value = false }
onMounted(loadSessions)
</script>

<style scoped>
.session-list { width: 100%; }
.list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
.sessions-grid { display: grid; gap: 16px; }
.session-card { display: flex; flex-wrap: wrap; gap: 20px; background: var(--card-bg); border-radius: 12px; padding: 20px; border: 1px solid var(--border-color); }
.session-card.current { border-color: var(--primary-600); background: var(--primary-50); }
.session-device { display: flex; align-items: center; gap: 8px; min-width: 150px; font-weight: 500; }
.session-device i { font-size: 24px; color: var(--primary-600); }
.session-info { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px; }
.info-row .label { font-size: 12px; color: var(--text-secondary); margin-right: 8px; }
.info-row .value { font-size: 13px; }
.session-actions { display: flex; align-items: center; min-width: 120px; justify-content: flex-end; }
.current-badge { background: var(--success); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
</style>