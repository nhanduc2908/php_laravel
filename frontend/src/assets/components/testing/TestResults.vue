<template>
    <div class="test-results">
        <div class="results-header"><h3>Kết quả kiểm thử</h3><div class="results-stats"><div class="stat total"><span>Tổng số:</span><strong>{{ stats.total }}</strong></div><div class="stat passed"><span>Đã qua:</span><strong>{{ stats.passed }}</strong></div><div class="stat failed"><span>Thất bại:</span><strong>{{ stats.failed }}</strong></div><div class="stat skipped"><span>Bỏ qua:</span><strong>{{ stats.skipped }}</strong></div><div class="stat duration"><span>Thời gian:</span><strong>{{ stats.duration }}ms</strong></div></div></div>
        <div class="results-list"><div v-for="result in results" :key="result.id" class="result-item" :class="result.status"><div class="result-header"><span class="result-name">{{ result.test_name }}</span><span class="result-status">{{ getStatusLabel(result.status) }}</span><span class="result-duration">{{ result.duration }}ms</span></div>
        <div class="result-message" v-if="result.message">{{ result.message }}</div>
        <div class="result-trace" v-if="result.trace && result.status === 'failed'"><pre>{{ result.trace }}</pre></div></div></div>
        <Pagination v-if="pagination && totalPages > 1" :current-page="currentPage" :total-pages="totalPages" @page-change="loadResults" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Pagination from '@/components/common/Pagination.vue'

const results = ref([])
const currentPage = ref(1)
const totalPages = ref(1)
const pagination = ref(true)

const stats = computed(() => {
    const total = results.value.length
    const passed = results.value.filter(r => r.status === 'passed').length
    const failed = results.value.filter(r => r.status === 'failed').length
    const skipped = results.value.filter(r => r.status === 'skipped').length
    const duration = results.value.reduce((sum, r) => sum + (r.duration || 0), 0)
    return { total, passed, failed, skipped, duration: duration.toFixed(2) }
})

const getStatusLabel = (status) => ({ passed: 'Đã qua', failed: 'Thất bại', skipped: 'Bỏ qua' }[status] || status)

const loadResults = async () => {
    const res = await fetch(`/api/v1/testing/results?page=${currentPage.value}`)
    const data = await res.json()
    results.value = data.data?.data || []
    totalPages.value = data.data?.last_page || 1
}

onMounted(loadResults)
</script>

<style scoped>
.test-results { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
.results-stats { display: flex; gap: 20px; flex-wrap: wrap; }
.stat { display: flex; gap: 6px; font-size: 14px; }
.stat.total strong { color: var(--text-primary); }
.stat.passed strong { color: var(--success); }
.stat.failed strong { color: var(--danger); }
.stat.skipped strong { color: var(--warning); }
.results-list { display: flex; flex-direction: column; gap: 12px; }
.result-item { padding: 16px; border-radius: 8px; background: var(--bg-secondary); border-left: 4px solid; }
.result-item.passed { border-left-color: var(--success); }
.result-item.failed { border-left-color: var(--danger); }
.result-item.skipped { border-left-color: var(--warning); }
.result-header { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; margin-bottom: 8px; }
.result-name { font-weight: 500; flex: 1; }
.result-status { font-size: 12px; padding: 2px 8px; border-radius: 12px; }
.result-item.passed .result-status { background: var(--success); color: white; }
.result-item.failed .result-status { background: var(--danger); color: white; }
.result-item.skipped .result-status { background: var(--warning); color: white; }
.result-duration { font-size: 12px; color: var(--text-secondary); font-family: monospace; }
.result-message { font-size: 13px; color: var(--text-secondary); margin-top: 8px; }
.result-trace pre { background: var(--bg-primary); padding: 12px; border-radius: 6px; font-size: 11px; overflow-x: auto; margin-top: 8px; }
</style>