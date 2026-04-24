<template>
    <div class="restore-confirm">
        <div class="confirm-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h3>Xác nhận phục hồi dữ liệu</h3>
        <div class="confirm-message">
            <p>Bạn có chắc chắn muốn phục hồi từ backup <strong>{{ backup.filename }}</strong>?</p>
            <div class="warning-box"><i class="fas fa-warning"></i> Cảnh báo: Hành động này sẽ ghi đè dữ liệu hiện tại!</div>
            <div class="backup-info"><p><strong>Loại:</strong> {{ getTypeLabel(backup.type) }}</p><p><strong>Kích thước:</strong> {{ formatSize(backup.size) }}</p><p><strong>Ngày tạo:</strong> {{ formatDate(backup.created_at) }}</p></div>
        </div>
        <div class="confirm-actions">
            <button class="btn btn-secondary" @click="$emit('cancel')">Hủy bỏ</button>
            <button class="btn btn-danger" @click="confirmRestore" :disabled="restoring"><i v-if="restoring" class="fas fa-spinner fa-spin"></i> Xác nhận phục hồi</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({ backup: { type: Object, required: true } })
const emit = defineEmits(['confirm', 'cancel'])
const restoring = ref(false)

const getTypeLabel = (type) => ({ database: 'Database', files: 'Files', both: 'Toàn bộ' }[type] || type)
const formatSize = (bytes) => { if (!bytes) return '0 B'; const k = 1024; const sizes = ['B', 'KB', 'MB', 'GB']; const i = Math.floor(Math.log(bytes) / Math.log(k)); return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i] }
const formatDate = (date) => new Date(date).toLocaleString('vi-VN')
const confirmRestore = () => { restoring.value = true; emit('confirm', props.backup.id); restoring.value = false }
</script>

<style scoped>
.restore-confirm { text-align: center; padding: 20px; }
.confirm-icon { font-size: 60px; color: var(--warning); margin-bottom: 20px; }
h3 { margin-bottom: 16px; }
.confirm-message { margin-bottom: 24px; }
.warning-box { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin: 16px 0; display: flex; align-items: center; gap: 8px; justify-content: center; }
.backup-info { background: var(--bg-secondary); padding: 16px; border-radius: 8px; text-align: left; }
.backup-info p { margin: 8px 0; }
.confirm-actions { display: flex; justify-content: center; gap: 12px; }
</style>