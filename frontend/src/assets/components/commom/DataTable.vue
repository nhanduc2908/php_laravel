<template>
    <div class="data-table-wrapper">
        <div class="table-responsive">
            <table class="data-table" :class="{ striped, bordered, hover }">
                <thead><tr><th v-for="col in columns" :key="col.key" :style="{ width: col.width }" :class="{ sortable: col.sortable }" @click="col.sortable && handleSort(col.key)">
                    {{ col.label }} <i v-if="col.sortable" :class="getSortIcon(col.key)"></i>
                </th><th v-if="actions">Thao tác</th></tr></thead>
                <tbody><tr v-for="(row, idx) in data" :key="idx"><td v-for="col in columns" :key="col.key">{{ formatValue(row[col.key], col) }}</td>
                <td v-if="actions"><div class="actions-cell"><slot name="actions" :row="row"></slot></div></td></tr>
                <tr v-if="loading"><td :colspan="columns.length + (actions ? 1 : 0)" class="text-center"><LoadingSpinner :is-loading="true" size="sm" /></td></tr>
                <tr v-if="!loading && data.length === 0"><td :colspan="columns.length + (actions ? 1 : 0)" class="text-center">Không có dữ liệu</td></tr></tbody>
            </table>
        </div>
        <Pagination v-if="pagination" :current-page="currentPage" :total-pages="totalPages" :total-items="totalItems" @page-change="onPageChange" />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import LoadingSpinner from './LoadingSpinner.vue'
import Pagination from './Pagination.vue'

const props = defineProps({ columns: { type: Array, required: true }, data: { type: Array, default: () => [] }, loading: Boolean, striped: Boolean, bordered: Boolean, hover: Boolean, actions: Boolean, pagination: Boolean, currentPage: { type: Number, default: 1 }, totalPages: { type: Number, default: 1 }, totalItems: { type: Number, default: 0 } })
const emit = defineEmits(['sort', 'page-change'])
const sortKey = ref(''), sortOrder = ref('asc')

const formatValue = (value, col) => { if (col.formatter) return col.formatter(value); if (col.type === 'date') return new Date(value).toLocaleDateString('vi-VN'); if (col.type === 'datetime') return new Date(value).toLocaleString('vi-VN'); if (col.type === 'currency') return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value); return value ?? '-' }
const getSortIcon = (key) => { if (sortKey.value !== key) return 'fas fa-sort'; return sortOrder.value === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down' }
const handleSort = (key) => { sortOrder.value = sortKey.value === key && sortOrder.value === 'asc' ? 'desc' : 'asc'; sortKey.value = key; emit('sort', { key: sortKey.value, order: sortOrder.value }) }
const onPageChange = (page) => emit('page-change', page)
</script>

<style scoped>
.data-table-wrapper { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 12px 16px; text-align: left; }
.data-table th { font-weight: 600; background: var(--bg-secondary); border-bottom: 2px solid var(--border-color); }
.data-table.striped tbody tr:nth-child(even) { background: var(--bg-secondary); }
.data-table.bordered th, .data-table.bordered td { border: 1px solid var(--border-color); }
.data-table.hover tbody tr:hover { background: var(--bg-secondary); }
.sortable { cursor: pointer; user-select: none; }
.sortable i { margin-left: 5px; font-size: 0.8rem; }
.actions-cell { display: flex; gap: 8px; }
</style>