<template>
    <div class="export-options">
        <div class="options-header"><h3>Xuất báo cáo</h3></div>
        <div class="options-body">
            <div class="option-group"><label class="option-label">Định dạng xuất:</label>
                <div class="format-buttons"><button v-for="fmt in formats" :key="fmt.value" class="format-btn" :class="{ active: selectedFormat === fmt.value }" @click="selectedFormat = fmt.value"><i :class="fmt.icon"></i> {{ fmt.label }}</button></div>
            </div>
            <div class="option-group"><label class="option-label">Tùy chọn:</label>
                <label class="checkbox"><input type="checkbox" v-model="includeCharts"> Bao gồm biểu đồ</label>
                <label class="checkbox"><input type="checkbox" v-model="includeDetails"> Bao gồm chi tiết</label>
                <label class="checkbox"><input type="checkbox" v-model="includeSummary"> Bao gồm tóm tắt</label>
            </div>
            <div class="option-group"><label class="option-label">Phạm vi dữ liệu:</label>
                <select v-model="dateRange"><option value="all">Tất cả</option><option value="last30">30 ngày qua</option><option value="last90">90 ngày qua</option><option value="this_year">Năm nay</option></select>
            </div>
            <div class="option-actions"><button class="btn btn-primary" @click="exportReport" :disabled="exporting"><i v-if="exporting" class="fas fa-spinner fa-spin"></i> Xuất báo cáo</button></div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const emit = defineEmits(['export'])

const formats = [{ value: 'pdf', label: 'PDF', icon: 'fas fa-file-pdf' }, { value: 'excel', label: 'Excel', icon: 'fas fa-file-excel' }, { value: 'csv', label: 'CSV', icon: 'fas fa-file-csv' }]
const selectedFormat = ref('pdf')
const includeCharts = ref(true)
const includeDetails = ref(true)
const includeSummary = ref(true)
const dateRange = ref('all')
const exporting = ref(false)

const exportReport = () => {
    exporting.value = true
    emit('export', { format: selectedFormat.value, options: { includeCharts: includeCharts.value, includeDetails: includeDetails.value, includeSummary: includeSummary.value, dateRange: dateRange.value } })
    setTimeout(() => { exporting.value = false }, 1000)
}
</script>

<style scoped>
.export-options { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.option-group { margin-bottom: 20px; }
.option-label { display: block; font-weight: 500; margin-bottom: 10px; }
.format-buttons { display: flex; gap: 12px; flex-wrap: wrap; }
.format-btn { display: flex; align-items: center; gap: 8px; padding: 8px 16px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); cursor: pointer; transition: all 0.2s; }
.format-btn.active { background: var(--primary-600); color: white; border-color: var(--primary-600); }
.checkbox { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; }
select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); min-width: 200px; }
.option-actions { margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color); text-align: right; }
</style>