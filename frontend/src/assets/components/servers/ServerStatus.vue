<template>
    <div class="server-status" :class="status">
        <span class="status-dot"></span>
        <span class="status-text">{{ statusText }}</span>
        <span class="status-time" v-if="lastCheck">Cập nhật: {{ formatTime(lastCheck) }}</span>
    </div>
</template>

<script setup>
const props = defineProps({ status: { type: String, default: 'unknown' }, lastCheck: { type: String, default: null } })

const statusText = { active: 'Hoạt động', inactive: 'Không hoạt động', pending: 'Chờ xử lý', error: 'Lỗi', unknown: 'Không xác định' }[props.status] || props.status

const formatTime = (date) => new Date(date).toLocaleString('vi-VN')
</script>

<style scoped>
.server-status { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
.server-status.active { background: #d1fae5; color: #10b981; }
.server-status.inactive { background: #fee2e2; color: #ef4444; }
.server-status.pending { background: #fef3c7; color: #f59e0b; }
.server-status.error { background: #fee2e2; color: #dc2626; }
.server-status.unknown { background: #f3f4f6; color: #6b7280; }
.status-dot { width: 8px; height: 8px; border-radius: 50%; }
.server-status.active .status-dot { background: #10b981; }
.server-status.inactive .status-dot { background: #ef4444; }
.server-status.pending .status-dot { background: #f59e0b; }
.status-time { font-size: 10px; opacity: 0.7; margin-left: 4px; }
</style>