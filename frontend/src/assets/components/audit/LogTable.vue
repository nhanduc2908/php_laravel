<template>
    <div class="log-table-wrapper">
        <DataTable
            :columns="columns"
            :data="logs"
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
            @row-click="viewDetail"
        >
            <template #actions="{ row }">
                <button class="action-btn" @click.stop="viewDetail(row)" title="Chi tiết">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn" @click.stop="exportLog(row)" title="Xuất">
                    <i class="fas fa-download"></i>
                </button>
            </template>
        </DataTable>
    </div>
</template>

<script setup>
import DataTable from '@/components/common/DataTable.vue'

const props = defineProps({
    logs: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    currentPage: { type: Number, default: 1 },
    totalPages: { type: Number, default: 1 },
    totalItems: { type: Number, default: 0 }
})

const emit = defineEmits(['view', 'export', 'sort', 'page-change'])

const columns = [
    { key: 'id', label: 'ID', width: '60px', sortable: true },
    { key: 'created_at', label: 'Thời gian', type: 'datetime', width: '180px', sortable: true },
    { key: 'user_name', label: 'Người dùng', sortable: true },
    { key: 'action', label: 'Hành động', width: '120px', formatter: (v) => `<span class="action-badge ${v}">${getActionLabel(v)}</span>` },
    { key: 'resource', label: 'Tài nguyên', width: '120px', sortable: true },
    { key: 'resource_id', label: 'ID tài nguyên', width: '100px' },
    { key: 'ip', label: 'IP', width: '130px' }
]

const getActionLabel = (action) => {
    const labels = { login: 'Đăng nhập', logout: 'Đăng xuất', create: 'Tạo', update: 'Cập nhật', delete: 'Xóa', view: 'Xem', export: 'Xuất' }
    return labels[action] || action
}

const viewDetail = (row) => emit('view', row)
const exportLog = (row) => emit('export', row)
const handleSort = (sort) => emit('sort', sort)
const handlePageChange = (page) => emit('page-change', page)
</script>

<style scoped>
.log-table-wrapper { width: 100%; overflow-x: auto; }
.action-badge { display: inline-block; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; }
.action-badge.login { background: #dbeafe; color: #3b82f6; }
.action-badge.logout { background: #fef3c7; color: #f59e0b; }
.action-badge.create { background: #d1fae5; color: #10b981; }
.action-badge.update { background: #e0e7ff; color: #4f46e5; }
.action-badge.delete { background: #fee2e2; color: #ef4444; }
.action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.action-btn:hover { background: var(--bg-secondary); color: var(--primary-600); }
</style>