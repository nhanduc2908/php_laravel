<template>
    <div class="criteria-table-wrapper">
        <DataTable
            :columns="columns"
            :data="criteria"
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
                <button class="action-btn" @click="viewCriteria(row)" title="Chi tiết">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn" @click="editCriteria(row)" title="Sửa">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn danger" @click="deleteCriteria(row)" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </template>
        </DataTable>
    </div>
</template>

<script setup>
import DataTable from '@/components/common/DataTable.vue'

const props = defineProps({
    criteria: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    currentPage: { type: Number, default: 1 },
    totalPages: { type: Number, default: 1 },
    totalItems: { type: Number, default: 0 }
})

const emit = defineEmits(['view', 'edit', 'delete', 'sort', 'page-change'])

const columns = [
    { key: 'code', label: 'Mã', width: '100px', sortable: true },
    { key: 'name', label: 'Tên tiêu chí', sortable: true },
    { key: 'category_name', label: 'Danh mục', sortable: true },
    { key: 'weight', label: 'Trọng số', width: '100px', sortable: true, formatter: (v) => `<span class="weight-badge">${v}</span>` },
    { key: 'status', label: 'Trạng thái', width: '100px', formatter: (v) => `<span class="status-badge ${v}">${v === 'active' ? 'Hoạt động' : 'Không hoạt động'}</span>` },
    { key: 'updated_at', label: 'Cập nhật', type: 'datetime', sortable: true }
]

const viewCriteria = (row) => emit('view', row)
const editCriteria = (row) => emit('edit', row)
const deleteCriteria = (row) => emit('delete', row)
const handleSort = (sort) => emit('sort', sort)
const handlePageChange = (page) => emit('page-change', page)
</script>

<style scoped>
.criteria-table-wrapper { width: 100%; overflow-x: auto; }
.weight-badge { display: inline-block; padding: 4px 8px; background: var(--primary-50); color: var(--primary-600); border-radius: 20px; font-size: 12px; font-weight: 500; }
.status-badge { display: inline-block; padding: 4px 8px; border-radius: 20px; font-size: 12px; }
.status-badge.active { background: #d1fae5; color: #10b981; }
.status-badge.inactive { background: #fee2e2; color: #ef4444; }
.action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.action-btn:hover { background: var(--bg-secondary); color: var(--primary-600); }
.action-btn.danger:hover { color: var(--danger); }
</style>