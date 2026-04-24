<template>
    <div class="alert-list">
        <div class="list-header">
            <h3>Cảnh báo hệ thống</h3>
            <div class="filter-group">
                <select v-model="severityFilter" class="filter-select">
                    <option value="">Tất cả mức độ</option>
                    <option value="critical">Nghiêm trọng</option>
                    <option value="high">Cao</option>
                    <option value="medium">Trung bình</option>
                    <option value="low">Thấp</option>
                </select>
                <select v-model="statusFilter" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="unread">Chưa đọc</option>
                    <option value="read">Đã đọc</option>
                    <option value="resolved">Đã giải quyết</option>
                </select>
                <button class="btn-secondary btn-sm" @click="markAllRead" :disabled="unreadCount === 0">
                    <i class="fas fa-check-double"></i> Đánh dấu tất cả đã đọc
                </button>
            </div>
        </div>
        <div v-if="loading" class="loading-state"><LoadingSpinner :is-loading="true" /></div>
        <div v-else-if="filteredAlerts.length === 0" class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <p>Không có cảnh báo nào</p>
        </div>
        <div v-else class="alerts-container">
            <div v-for="alert in filteredAlerts" :key="alert.id" class="alert-item" :class="[alert.severity, { unread: !alert.is_read, resolved: alert.is_resolved }]">
                <div class="alert-icon"><i :class="getAlertIcon(alert.type)"></i></div>
                <div class="alert-content">
                    <div class="alert-header">
                        <span class="alert-title">{{ alert.title }}</span>
                        <span class="alert-time">{{ formatTime(alert.created_at) }}</span>
                    </div>
                    <div class="alert-message">{{ alert.message }}</div>
                    <div class="alert-footer">
                        <span class="alert-source" v-if="alert.source"><i class="fas fa-info-circle"></i> {{ alert.source }}</span>
                        <div class="alert-actions">
                            <button v-if="!alert.is_read" class="action-btn" @click="markRead(alert.id)"><i class="fas fa-check"></i> Đánh dấu đã đọc</button>
                            <button v-if="!alert.is_resolved" class="action-btn resolve" @click="resolve(alert.id)"><i class="fas fa-check-double"></i> Giải quyết</button>
                            <button class="action-btn" @click="viewDetail(alert.id)"><i class="fas fa-eye"></i> Chi tiết</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Pagination v-if="pagination && totalPages > 1" :current-page="currentPage" :total-pages="totalPages" @page-change="loadAlerts" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import Pagination from '@/components/common/Pagination.vue'

const emit = defineEmits(['mark-read', 'resolve', 'view'])

const alerts = ref([])
const loading = ref(false)
const severityFilter = ref('')
const statusFilter = ref('')
const currentPage = ref(1)
const totalPages = ref(1)
const pagination = ref(true)

const unreadCount = computed(() => alerts.value.filter(a => !a.is_read && !a.is_resolved).length)

const filteredAlerts = computed(() => {
    let filtered = [...alerts.value]
    if (severityFilter.value) filtered = filtered.filter(a => a.severity === severityFilter.value)
    if (statusFilter.value === 'unread') filtered = filtered.filter(a => !a.is_read && !a.is_resolved)
    if (statusFilter.value === 'read') filtered = filtered.filter(a => a.is_read && !a.is_resolved)
    if (statusFilter.value === 'resolved') filtered = filtered.filter(a => a.is_resolved)
    return filtered
})

const getAlertIcon = (type) => {
    const icons = { vulnerability: 'fas fa-bug', assessment: 'fas fa-clipboard-list', system: 'fas fa-server', security: 'fas fa-shield-alt' }
    return icons[type] || 'fas fa-bell'
}

const formatTime = (date) => {
    const diff = new Date() - new Date(date)
    const minutes = Math.floor(diff / 60000)
    const hours = Math.floor(diff / 3600000)
    const days = Math.floor(diff / 86400000)
    if (minutes < 1) return 'Vừa xong'
    if (minutes < 60) return `${minutes} phút trước`
    if (hours < 24) return `${hours} giờ trước`
    return `${days} ngày trước`
}

const loadAlerts = async () => {
    loading.value = true
    const res = await fetch(`/api/v1/alerts?page=${currentPage.value}`)
    const data = await res.json()
    alerts.value = data.data?.data || []
    totalPages.value = data.data?.last_page || 1
    loading.value = false
}

const markRead = async (id) => {
    await fetch(`/api/v1/alerts/${id}/read`, { method: 'POST' })
    emit('mark-read', id)
    loadAlerts()
}

const markAllRead = async () => {
    const ids = alerts.value.filter(a => !a.is_read && !a.is_resolved).map(a => a.id)
    if (ids.length) {
        await fetch('/api/v1/alerts/bulk-read', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ids }) })
        loadAlerts()
    }
}

const resolve = async (id) => {
    await fetch(`/api/v1/alerts/${id}/resolve`, { method: 'POST' })
    emit('resolve', id)
    loadAlerts()
}

const viewDetail = (id) => emit('view', id)

onMounted(loadAlerts)
</script>

<style scoped>
.alert-list { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
.filter-group { display: flex; gap: 12px; flex-wrap: wrap; }
.filter-select { padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
.alerts-container { display: flex; flex-direction: column; gap: 12px; }
.alert-item { display: flex; gap: 16px; padding: 16px; border-radius: 10px; background: var(--bg-secondary); border-left: 4px solid; transition: all 0.2s; }
.alert-item.critical { border-left-color: #ef4444; }
.alert-item.high { border-left-color: #f97316; }
.alert-item.medium { border-left-color: #eab308; }
.alert-item.low { border-left-color: #22c55e; }
.alert-item.unread { background: var(--primary-50); }
.alert-item.resolved { opacity: 0.7; }
.alert-icon { font-size: 20px; }
.alert-item.critical .alert-icon { color: #ef4444; }
.alert-item.high .alert-icon { color: #f97316; }
.alert-content { flex: 1; }
.alert-header { display: flex; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 8px; }
.alert-title { font-weight: 600; }
.alert-time { font-size: 12px; color: var(--text-secondary); }
.alert-message { font-size: 14px; color: var(--text-secondary); margin-bottom: 12px; }
.alert-footer { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
.alert-source { font-size: 12px; color: var(--text-secondary); }
.alert-actions { display: flex; gap: 12px; }
.action-btn { background: none; border: none; cursor: pointer; font-size: 12px; color: var(--primary-600); padding: 4px 8px; border-radius: 6px; transition: all 0.2s; }
.action-btn:hover { background: var(--bg-primary); }
.action-btn.resolve { color: var(--success); }
.empty-state, .loading-state { text-align: center; padding: 60px; color: var(--text-secondary); }
.empty-state i { font-size: 48px; margin-bottom: 16px; }
</style>