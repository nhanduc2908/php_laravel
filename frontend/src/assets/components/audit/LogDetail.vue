<template>
    <div class="log-detail">
        <div class="detail-header"><h3>Chi tiết audit log</h3><button class="close-btn" @click="close"><i class="fas fa-times"></i></button></div>
        <div class="detail-body">
            <div class="detail-row"><span class="label">ID:</span><span class="value">{{ log.id }}</span></div>
            <div class="detail-row"><span class="label">Thời gian:</span><span class="value">{{ formatDateTime(log.created_at) }}</span></div>
            <div class="detail-row"><span class="label">Người dùng:</span><span class="value">{{ log.user_name }} ({{ log.user_email }})</span></div>
            <div class="detail-row"><span class="label">Hành động:</span><span class="value"><span class="action-badge" :class="log.action">{{ getActionLabel(log.action) }}</span></span></div>
            <div class="detail-row"><span class="label">Tài nguyên:</span><span class="value">{{ log.resource }} #{{ log.resource_id }}</span></div>
            <div class="detail-row"><span class="label">IP:</span><span class="value">{{ log.ip }}</span></div>
            <div class="detail-row"><span class="label">User Agent:</span><span class="value user-agent">{{ log.user_agent }}</span></div>
            <div class="detail-section" v-if="log.old_values"><h4>Giá trị cũ</h4><pre class="json-view">{{ JSON.stringify(log.old_values, null, 2) }}</pre></div>
            <div class="detail-section" v-if="log.new_values"><h4>Giá trị mới</h4><pre class="json-view">{{ JSON.stringify(log.new_values, null, 2) }}</pre></div>
        </div>
        <div class="detail-footer"><button class="btn btn-secondary" @click="close">Đóng</button></div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ log: { type: Object, required: true } })
const emit = defineEmits(['close'])

const getActionLabel = (action) => {
    const labels = { login: 'Đăng nhập', logout: 'Đăng xuất', create: 'Tạo', update: 'Cập nhật', delete: 'Xóa', view: 'Xem', export: 'Xuất' }
    return labels[action] || action
}

const formatDateTime = (date) => date ? new Date(date).toLocaleString('vi-VN') : 'N/A'
const close = () => emit('close')
</script>

<style scoped>
.log-detail { background: var(--card-bg); border-radius: 12px; width: 600px; max-width: 90vw; max-height: 80vh; display: flex; flex-direction: column; overflow: hidden; }
.detail-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border-color); }
.detail-header h3 { margin: 0; }
.close-btn { background: none; border: none; cursor: pointer; font-size: 18px; color: var(--text-secondary); }
.detail-body { flex: 1; overflow-y: auto; padding: 20px; }
.detail-row { display: flex; margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid var(--border-color); }
.detail-row .label { width: 120px; font-weight: 600; flex-shrink: 0; }
.detail-row .value { flex: 1; word-break: break-word; }
.user-agent { font-size: 12px; font-family: monospace; color: var(--text-secondary); }
.action-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.action-badge.login { background: #dbeafe; color: #3b82f6; }
.action-badge.logout { background: #fef3c7; color: #f59e0b; }
.action-badge.create { background: #d1fae5; color: #10b981; }
.action-badge.update { background: #e0e7ff; color: #4f46e5; }
.action-badge.delete { background: #fee2e2; color: #ef4444; }
.detail-section { margin-top: 20px; padding-top: 12px; border-top: 1px solid var(--border-color); }
.detail-section h4 { font-size: 14px; margin-bottom: 10px; }
.json-view { background: var(--bg-secondary); padding: 12px; border-radius: 8px; font-size: 12px; font-family: monospace; overflow-x: auto; }
.detail-footer { padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; }
</style>