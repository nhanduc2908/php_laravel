<template>
    <div class="recent-activities">
        <div class="activities-header">
            <h3>Hoạt động gần đây</h3>
            <router-link to="/audit" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></router-link>
        </div>
        <div class="activities-list">
            <div v-for="activity in activities" :key="activity.id" class="activity-item">
                <div class="activity-icon" :class="activity.type">
                    <i :class="getActivityIcon(activity.action)"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">{{ activity.title }}</div>
                    <div class="activity-description">{{ activity.description }}</div>
                    <div class="activity-time">{{ formatTime(activity.created_at) }}</div>
                </div>
                <div class="activity-user">
                    <img :src="activity.user_avatar || '/assets/images/default-avatar.png'" alt="Avatar" class="user-avatar">
                    <span>{{ activity.user_name }}</span>
                </div>
            </div>
            <div v-if="activities.length === 0" class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Chưa có hoạt động nào</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useDashboardStore } from '@/stores/dashboard'

const props = defineProps({ limit: { type: Number, default: 10 } })
const dashboardStore = useDashboardStore()
const activities = ref([])

const getActivityIcon = (action) => {
    const icons = { login: 'fas fa-sign-in-alt', logout: 'fas fa-sign-out-alt', create: 'fas fa-plus-circle', update: 'fas fa-edit', delete: 'fas fa-trash-alt', assessment: 'fas fa-clipboard-list', scan: 'fas fa-search', upload: 'fas fa-upload', share: 'fas fa-share-alt' }
    return icons[action] || 'fas fa-bell'
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

const fetchActivities = async () => { activities.value = await dashboardStore.getRecentActivities(props.limit) }
onMounted(fetchActivities)
</script>

<style scoped>
.recent-activities { background: var(--card-bg); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); }
.activities-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.activities-header h3 { margin: 0; }
.view-all { font-size: 14px; color: var(--primary-600); text-decoration: none; }
.activity-item { display: flex; align-items: center; gap: 16px; padding: 12px 0; border-bottom: 1px solid var(--border-color); }
.activity-item:last-child { border-bottom: none; }
.activity-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.activity-icon.login { background: #dbeafe; color: #3b82f6; }
.activity-icon.create { background: #d1fae5; color: #10b981; }
.activity-icon.update { background: #fef3c7; color: #f59e0b; }
.activity-icon.delete { background: #fee2e2; color: #ef4444; }
.activity-content { flex: 1; }
.activity-title { font-weight: 500; margin-bottom: 4px; }
.activity-description { font-size: 13px; color: var(--text-secondary); }
.activity-time { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }
.activity-user { display: flex; align-items: center; gap: 8px; font-size: 13px; }
.user-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
.empty-state { text-align: center; padding: 40px; color: var(--text-secondary); }
.empty-state i { font-size: 48px; margin-bottom: 16px; }
</style>