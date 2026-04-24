<template>
    <div class="environment-info">
        <div class="info-header"><i class="fas fa-server"></i><h3>Thông tin môi trường</h3><button class="refresh-btn" @click="refresh" :disabled="refreshing"><i class="fas fa-sync-alt" :class="{ spinning: refreshing }"></i></button></div>
        <div class="info-grid"><div class="info-card"><div class="info-icon"><i class="fas fa-code-branch"></i></div><div class="info-content"><span class="info-label">Phiên bản ứng dụng</span><span class="info-value">{{ info.app_version }}</span></div></div>
        <div class="info-card"><div class="info-icon"><i class="fab fa-php"></i></div><div class="info-content"><span class="info-label">PHP Version</span><span class="info-value">{{ info.php_version }}</span></div></div>
        <div class="info-card"><div class="info-icon"><i class="fas fa-database"></i></div><div class="info-content"><span class="info-label">Database</span><span class="info-value">{{ info.db_type }} {{ info.db_version }}</span></div></div>
        <div class="info-card"><div class="info-icon"><i class="fas fa-microchip"></i></div><div class="info-content"><span class="info-label">Server OS</span><span class="info-value">{{ info.os }}</span></div></div>
        <div class="info-card"><div class="info-icon"><i class="fas fa-memory"></i></div><div class="info-content"><span class="info-label">Memory Usage</span><span class="info-value">{{ info.memory_usage }}</span></div></div>
        <div class="info-card"><div class="info-icon"><i class="fas fa-hdd"></i></div><div class="info-content"><span class="info-label">Disk Usage</span><span class="info-value">{{ info.disk_usage }} / {{ info.disk_total }}</span></div></div>
        <div class="info-card"><div class="info-icon"><i class="fas fa-chart-line"></i></div><div class="info-content"><span class="info-label">Load Average</span><span class="info-value">{{ info.load_average }}</span></div></div>
        <div class="info-card"><div class="info-icon"><i class="fas fa-clock"></i></div><div class="info-content"><span class="info-label">Server Time</span><span class="info-value">{{ info.server_time }}</span></div></div></div>
        <div class="info-footer"><div class="status" :class="{ online: info.status === 'healthy' }"><i class="fas fa-circle"></i> {{ info.status === 'healthy' ? 'Hệ thống hoạt động bình thường' : 'Có vấn đề về hệ thống' }}</div></div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

const info = reactive({ app_version: '-', php_version: '-', db_type: '-', db_version: '-', os: '-', memory_usage: '-', disk_usage: '-', disk_total: '-', load_average: '-', server_time: '-', status: 'healthy' })
const refreshing = ref(false)

const loadInfo = async () => {
    refreshing.value = true
    const res = await fetch('/api/v1/system/info')
    const data = await res.json()
    Object.assign(info, data.data || {})
    refreshing.value = false
}

const refresh = () => loadInfo()
onMounted(loadInfo)
</script>

<style scoped>
.environment-info { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.info-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.info-header i { font-size: 24px; color: var(--primary-600); }
.info-header h3 { margin: 0; flex: 1; }
.refresh-btn { background: none; border: none; cursor: pointer; font-size: 16px; color: var(--text-secondary); padding: 4px; }
.spinning { animation: spin 1s linear infinite; }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; margin-bottom: 20px; }
.info-card { display: flex; align-items: center; gap: 16px; padding: 16px; background: var(--bg-secondary); border-radius: 10px; }
.info-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--primary-50); color: var(--primary-600); display: flex; align-items: center; justify-content: center; font-size: 20px; }
.info-content { flex: 1; }
.info-label { display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px; }
.info-value { font-size: 16px; font-weight: 600; }
.info-footer { padding-top: 16px; border-top: 1px solid var(--border-color); }
.status { display: flex; align-items: center; gap: 8px; font-size: 14px; }
.status.online i { color: #10b981; }
.status i { font-size: 10px; }
</style>