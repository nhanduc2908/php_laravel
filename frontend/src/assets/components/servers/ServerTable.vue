<template>
    <div class="server-table-wrapper">
        <DataTable
            :columns="columns"
            :data="servers"
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
                <button class="action-btn" @click="testConnection(row)" title="Test kết nối">
                    <i class="fas fa-plug"></i>
                </button>
                <button class="action-btn" @click="scanServer(row)" title="Quét">
                    <i class="fas fa-search"></i>
                </button>
                <button class="action-btn" @click="viewDetail(row)" title="Chi tiết">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn" @click="editServer(row)" title="Sửa">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn danger" @click="deleteServer(row)" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </template>
        </DataTable>
    </div>
</template>

<script setup>
import DataTable from '@/components/common/DataTable.vue'

const props = defineProps({
    servers: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    currentPage: { type: Number, default: 1 },
    totalPages: { type: Number, default: 1 },
    totalItems: { type: Number, default: 0 }
})

const emit = defineEmits(['test', 'scan', 'view', 'edit', 'delete', 'sort', 'page-change'])

const columns = [
    { key: 'id', label: 'ID', width: '60px', sortable: true },
    { key: 'name', label: 'Tên máy chủ', sortable: true },
    { key: 'host', label: 'Địa chỉ IP' },
    { key: 'port', label: 'Cổng', width: '80px' },
    { key: 'status', label: 'Trạng thái', formatter: (v) => `<ServerStatus :status="'${v}'" />` },
    { key: 'score', label: 'Điểm', sortable: true, formatter: (v) => `<span class="score-badge">${v || 0}%</span>` },
    { key: 'last_scan_at', label: 'Quét lần cuối', type: 'datetime', sortable: true }
]

const testConnection = (server) => emit('test', server)
const scanServer = (server) => emit('scan', server)
const viewDetail = (server) => emit('view', server)
const editServer = (server) => emit('edit', server)
const deleteServer = (server) => emit('delete', server)
const handleSort = (sort) => emit('sort', sort)
const handlePageChange = (page) => emit('page-change', page)
</script>

<style scoped>
.server-table-wrapper { width: 100%; overflow-x: auto; }
.score-badge { display: inline-block; padding: 4px 8px; background: var(--primary-50); color: var(--primary-600); border-radius: 20px; font-size: 12px; font-weight: 500; }
.action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.action-btn:hover { background: var(--bg-secondary); color: var(--primary-600); }
.action-btn.danger:hover { color: var(--danger); }
</style>