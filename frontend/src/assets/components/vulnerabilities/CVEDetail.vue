<template>
    <div class="cve-detail" v-if="cveData">
        <div class="cve-header">
            <h2>{{ cveData.id }}</h2>
            <SeverityBadge :severity="cveData.severity" />
        </div>
        <div class="cve-section"><h3>Mô tả</h3><p>{{ cveData.description }}</p></div>
        <div class="cve-stats"><div class="stat"><span class="label">CVSS Score:</span><span class="value" :class="scoreClass">{{ cveData.cvss_score }}</span></div>
        <div class="stat"><span class="label">Ngày công bố:</span><span class="value">{{ formatDate(cveData.published) }}</span></div>
        <div class="stat"><span class="label">Cập nhật lần cuối:</span><span class="value">{{ formatDate(cveData.last_modified) }}</span></div></div>
        <div class="cve-section" v-if="cveData.references?.length"><h3>Tham khảo</h3><ul><li v-for="ref in cveData.references" :key="ref"><a :href="ref" target="_blank" rel="noopener">{{ ref }}</a></li></ul></div>
        <div class="cve-actions"><a :href="`https://nvd.nist.gov/vuln/detail/${cveData.id}`" target="_blank" class="btn btn-outline">Xem trên NVD <i class="fas fa-external-link-alt"></i></a></div>
    </div>
    <div v-else class="cve-loading"><LoadingSpinner :is-loading="true" text="Đang tải thông tin CVE..." /></div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import SeverityBadge from './SeverityBadge.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const props = defineProps({ cveId: { type: String, required: true } })
const cveData = ref(null)

const scoreClass = computed(() => { const score = cveData.value?.cvss_score; if (score >= 7) return 'high'; if (score >= 4) return 'medium'; return 'low' })

const formatDate = (date) => date ? new Date(date).toLocaleDateString('vi-VN') : 'N/A'

const fetchCVEData = async () => {
    const response = await fetch(`/api/v1/vulnerabilities/cve/${props.cveId}`)
    cveData.value = (await response.json()).data
}

onMounted(fetchCVEData)
</script>

<style scoped>
.cve-detail { background: var(--card-bg); border-radius: 12px; padding: 24px; }
.cve-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
.cve-header h2 { font-family: monospace; margin: 0; }
.cve-section { margin: 20px 0; }
.cve-section h3 { font-size: 16px; margin-bottom: 12px; }
.cve-stats { display: flex; gap: 24px; padding: 16px; background: var(--bg-secondary); border-radius: 8px; margin: 20px 0; flex-wrap: wrap; }
.stat .label { font-size: 13px; color: var(--text-secondary); margin-right: 8px; }
.stat .value { font-weight: 600; }
.stat .value.high { color: #ef4444; }
.stat .value.medium { color: #f59e0b; }
.stat .value.low { color: #10b981; }
.cve-section ul { list-style: none; padding-left: 0; }
.cve-section li { margin-bottom: 8px; word-break: break-all; }
.cve-section a { color: var(--primary-600); text-decoration: none; }
.cve-section a:hover { text-decoration: underline; }
.cve-actions { margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color); }
</style>