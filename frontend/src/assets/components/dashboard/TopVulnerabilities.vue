<template>
    <div class="top-vulnerabilities">
        <div class="vuln-header">
            <h3>Top lỗ hổng nghiêm trọng</h3>
            <router-link to="/vulnerabilities" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></router-link>
        </div>
        <div class="vuln-list">
            <div v-for="vuln in vulnerabilities" :key="vuln.id" class="vuln-item">
                <div class="vuln-severity" :class="vuln.severity">
                    <span class="severity-dot"></span>
                    <span class="severity-label">{{ getSeverityLabel(vuln.severity) }}</span>
                </div>
                <div class="vuln-info">
                    <div class="vuln-name">{{ vuln.name }}</div>
                    <div class="vuln-server"><i class="fas fa-server"></i> {{ vuln.server_name }}</div>
                </div>
                <div class="vuln-cve">{{ vuln.cve || 'N/A' }}</div>
                <div class="vuln-score" :class="getScoreClass(vuln.cvss_score)">{{ vuln.cvss_score || 'N/A' }}</div>
            </div>
            <div v-if="vulnerabilities.length === 0" class="empty-state"><i class="fas fa-shield-alt"></i><p>Chưa có lỗ hổng nào</p></div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const vulnerabilities = ref([])

const getSeverityLabel = (severity) => ({ critical: 'Nghiêm trọng', high: 'Cao', medium: 'Trung bình', low: 'Thấp' }[severity] || severity)
const getScoreClass = (score) => { if (!score) return ''; if (score >= 7) return 'high'; if (score >= 4) return 'medium'; return 'low' }

onMounted(async () => { const response = await fetch('/api/v1/vulnerabilities?sort=cvss_score&order=desc&limit=5'); const data = await response.json(); vulnerabilities.value = data.data || [] })
</script>

<style scoped>
.top-vulnerabilities { background: var(--card-bg); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-sm); }
.vuln-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.vuln-header h3 { margin: 0; }
.vuln-list { display: flex; flex-direction: column; gap: 12px; }
.vuln-item { display: flex; align-items: center; gap: 16px; padding: 12px; background: var(--bg-secondary); border-radius: 10px; }
.vuln-severity { display: flex; align-items: center; gap: 6px; min-width: 100px; }
.severity-dot { width: 10px; height: 10px; border-radius: 50%; }
.vuln-severity.critical .severity-dot { background: #ef4444; }
.vuln-severity.high .severity-dot { background: #f97316; }
.vuln-severity.medium .severity-dot { background: #eab308; }
.vuln-severity.low .severity-dot { background: #22c55e; }
.vuln-info { flex: 1; }
.vuln-name { font-weight: 500; margin-bottom: 4px; }
.vuln-server { font-size: 12px; color: var(--text-secondary); }
.vuln-cve { font-family: monospace; font-size: 13px; color: var(--primary-600); min-width: 120px; }
.vuln-score { font-weight: 600; min-width: 40px; text-align: center; }
.vuln-score.high { color: #ef4444; }
.vuln-score.medium { color: #f97316; }
.vuln-score.low { color: #22c55e; }
</style>