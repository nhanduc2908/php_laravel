<template>
    <div class="coverage-report">
        <div class="coverage-header"><h3>Code Coverage</h3><div class="coverage-summary"><div class="coverage-circle"><svg viewBox="0 0 120 120"><circle cx="60" cy="60" r="54" fill="none" stroke="var(--bg-secondary)" stroke-width="8"/><circle cx="60" cy="60" r="54" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="339.292" :stroke-dashoffset="339.292 - (339.292 * coverage.total / 100)" transform="rotate(-90 60 60)"/></svg><div class="coverage-value">{{ coverage.total }}<span>%</span></div></div><div class="coverage-stats"><div class="stat"><span class="label">Lines:</span><span class="value">{{ coverage.lines }}%</span></div><div class="stat"><span class="label">Functions:</span><span class="value">{{ coverage.functions }}%</span></div><div class="stat"><span class="label">Branches:</span><span class="value">{{ coverage.branches }}%</span></div><div class="stat"><span class="label">Statements:</span><span class="value">{{ coverage.statements }}%</span></div></div></div></div>
        <div class="coverage-details"><div class="file-list"><div v-for="file in coverage.files" :key="file.path" class="file-item"><div class="file-header" @click="toggleFile(file.path)"><i class="fas fa-chevron-right" :class="{ rotated: expandedFiles.includes(file.path) }"></i><span class="file-name">{{ file.name }}</span><div class="file-coverage"><span class="coverage-badge" :class="getCoverageClass(file.coverage)">{{ file.coverage }}%</span></div></div>
        <div v-show="expandedFiles.includes(file.path)" class="file-details"><div class="line-coverage" v-for="line in file.lines" :key="line.line" class="line-item" :class="{ covered: line.covered, uncovered: !line.covered }"><span class="line-number">{{ line.line }}</span><span class="line-content">{{ line.content }}</span></div></div></div></div></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const coverage = ref({ total: 0, lines: 0, functions: 0, branches: 0, statements: 0, files: [] })
const expandedFiles = ref([])

const getCoverageClass = (value) => { if (value >= 80) return 'high'; if (value >= 60) return 'medium'; return 'low' }
const toggleFile = (path) => { const idx = expandedFiles.value.indexOf(path); idx > -1 ? expandedFiles.value.splice(idx, 1) : expandedFiles.value.push(path) }

const loadCoverage = async () => { const res = await fetch('/api/v1/testing/coverage'); coverage.value = (await res.json()).data || coverage.value }
onMounted(loadCoverage)
</script>

<style scoped>
.coverage-report { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.coverage-header { margin-bottom: 24px; }
.coverage-summary { display: flex; align-items: center; gap: 40px; flex-wrap: wrap; justify-content: center; }
.coverage-circle { position: relative; width: 120px; height: 120px; color: var(--primary-600); }
.coverage-circle svg { width: 100%; height: 100%; transform: rotate(-90deg); }
.coverage-value { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 28px; font-weight: 700; text-align: center; }
.coverage-value span { font-size: 14px; font-weight: 400; }
.coverage-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.stat .label { display: block; font-size: 12px; color: var(--text-secondary); }
.stat .value { font-size: 24px; font-weight: 700; }
.file-list { border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; }
.file-item { border-bottom: 1px solid var(--border-color); }
.file-header { display: flex; align-items: center; gap: 12px; padding: 12px 16px; cursor: pointer; background: var(--bg-secondary); }
.file-header:hover { background: var(--bg-primary); }
.file-header i { transition: transform 0.2s; font-size: 12px; }
.file-header i.rotated { transform: rotate(90deg); }
.file-name { flex: 1; font-weight: 500; }
.file-coverage { min-width: 80px; text-align: right; }
.coverage-badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 12px; }
.coverage-badge.high { background: #d1fae5; color: #10b981; }
.coverage-badge.medium { background: #fef3c7; color: #f59e0b; }
.coverage-badge.low { background: #fee2e2; color: #ef4444; }
.file-details { padding: 0; }
.line-item { display: flex; font-family: monospace; font-size: 12px; border-bottom: 1px solid var(--border-color); }
.line-number { width: 50px; padding: 4px 8px; background: var(--bg-secondary); color: var(--text-secondary); text-align: right; border-right: 1px solid var(--border-color); }
.line-content { flex: 1; padding: 4px 12px; white-space: pre; overflow-x: auto; }
.line-item.covered .line-content { background: #d1fae5; color: #10b981; }
.line-item.uncovered .line-content { background: #fee2e2; color: #ef4444; }
</style>