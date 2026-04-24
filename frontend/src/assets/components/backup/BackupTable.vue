<template>
    <div class="backup-table-wrapper">
        <DataTable
            :columns="columns"
            :data="backups"
            :loading="loading"
            :striped="true"
            :hover="true"
            :actions="true"
            :pagination="true"
            :current-page="currentPage"
            :total-pages="totalPages"
            :total-items="totalItems"
            @sort="handleSort"
            @page-change="handlePageChange"
        >
            <template #actions="{ row }">
                <button class="action-btn" @click="restoreBackup(row)" title="Phục hồi">
                    <i class="fas fa-undo-alt"></i>
                </button>
                <button class="action-btn" @click="downloadBackup(row)" title="Tải xuống">
                    <i class="fas fa-download"></i>
                </button>
                <button class="action-btn danger" @click="deleteBackup(row)" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </template>
        </DataTable>
    </div>
</template>

<script setup>
import DataTable from '@/components/common/DataTable.vue'

const props = defineProps({
    backups: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    currentPage: { type: Number, default: 1 },
    totalPages: { type: Number, default: 1 },
    totalItems: { type: Number, default: 0 }
})

const emit = defineEmits(['restore', 'download', 'delete', 'sort', 'page-change'])

const columns = [
    { key: 'id', label: 'ID', width: '60px', sortable: true },
    { key: 'filename', label: 'Tên file', sortable: true },
    { key: 'type', label: 'Loại', width: '100px', formatter: (v) => ({ database: 'Database', files: 'Files', both: 'Toàn bộ' }[v] || v) },
    { key: 'size', label: 'Kích thước', formatter: (v) => formatSize(v) },
    { key: 'status', label: 'Trạng thái', width: '100px', formatter: (v) => `<span class="status-badge ${v}">${v === 'completed' ? 'Hoàn tất' : (v === 'pending' ? 'Đang xử lý' : 'Thất bại')}</span>` },
    { key: 'created_at', label: 'Ngày tạo', type: 'datetime', sortable: true }
]

const formatSize = (bytes) => {
    if (!bytes) return '0 B'
    const k = 1024
    const sizes = ['B', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const restoreBackup = (row) => emit('restore', row)
const downloadBackup = (row) => emit('download', row)
const deleteBackup = (row) => emit('delete', row)
const handleSort = (sort) => emit('sort', sort)
const handlePageChange = (page) => emit('page-change', page)
</script>

<style scoped>
.backup-table-wrapper { width: 100%; overflow-x: auto; }
.status-badge { display: inline-block; padding: 4px 8px; border-radius: 20px; font-size: 12px; }
.status-badge.completed { background: #d1fae5; color: #10b981; }
.status-badge.pending { background: #fef3c7; color: #f59e0b; }
.status-badge.failed { background: #fee2e2; color: #ef4444; }
.action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.action-btn:hover { background: var(--bg-secondary); color: var(--primary-600); }
.action-btn.danger:hover { color: var(--danger); }
</style>