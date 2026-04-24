<template>
    <div class="history-table">
        <DataTable :columns="columns" :data="history" :loading="loading" :striped="true" :hover="true" :actions="true" :pagination="true" :current-page="currentPage" :total-pages="totalPages" :total-items="totalItems" @page-change="handlePageChange">
            <template #actions="{ row }">
                <button class="action-btn" @click="viewDetail(row)" title="Chi tiết"><i class="fas fa-eye"></i></button>
                <button class="action-btn" @click="compareWith(row)" title="So sánh"><i class="fas fa-chart-line"></i></button>
                <button class="action-btn" @click="exportReport(row)" title="Xuất báo cáo"><i class="fas fa-download"></i></button>
            </template>
        </DataTable>
    </div>
</template>

<script setup>
import DataTable from '@/components/common/DataTable.vue'

const props = defineProps({ history: { type: Array, default: () => [] }, loading: { type: Boolean, default: false }, currentPage: { type: Number, default: 1 }, totalPages: { type: Number, default: 1 }, totalItems: { type: Number, default: 0 } })
const emit = defineEmits(['view', 'compare', 'export', 'page-change'])

const columns = [
    { key: 'id', label: 'ID', width: '60px' },
    { key: 'server_name', label: 'Máy chủ', sortable: true },
    { key: 'total_score', label: 'Điểm', sortable: true, formatter: (v) => `<span class="score-value ${getScoreClass(v)}">${v}%</span>` },
    { key: 'compliance_percent', label: 'Tuân thủ', sortable: true, formatter: (v) => `${v}%` },
    { key: 'status', label: 'Trạng thái', formatter: (v) => `<span class="status-badge ${v}">${v === 'compliant' ? 'Đạt' : 'Không đạt'}</span>` },
    { key: 'created_by_name', label: 'Người đánh giá' },
    { key: 'created_at', label: 'Ngày đánh giá', type: 'datetime', sortable: true }
]

const getScoreClass = (score) => { if (score >= 80) return 'high'; if (score >= 60) return 'medium'; return 'low' }
const viewDetail = (row) => emit('view', row)
const compareWith = (row) => emit('compare', row)
const exportReport = (row) => emit('export', row)
const handlePageChange = (page) => emit('page-change', page)
</script>

<style scoped>
.history-table { width: 100%; overflow-x: auto; }
.score-value { font-weight: 600; }
.score-value.high { color: #10b981; }
.score-value.medium { color: #f59e0b; }
.score-value.low { color: #ef4444; }
.status-badge { display: inline-block; padding: 4px 8px; border-radius: 20px; font-size: 12px; }
.status-badge.compliant { background: #d1fae5; color: #10b981; }
.status-badge.non_compliant { background: #fee2e2; color: #ef4444; }
.action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.action-btn:hover { background: var(--bg-secondary); color: var(--primary-600); }
</style>