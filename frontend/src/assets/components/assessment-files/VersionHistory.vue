<template>
    <div class="version-history"><h3>Lịch sử phiên bản</h3><div class="version-timeline"><div v-for="version in versions" :key="version.id" class="version-item"><div class="version-badge">v{{ version.version }}</div><div class="version-info"><div class="version-date">{{ formatDate(version.created_at) }}</div><div class="version-user"><i class="fas fa-user"></i> {{ version.created_by_name }}</div><button v-if="!isCurrent(version)" class="restore-btn" @click="restore(version.id)"><i class="fas fa-undo"></i> Khôi phục</button></div></div></div></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({ fileId: { type: Number, required: true } })
const versions = ref([])

const loadVersions = async () => { const res = await fetch(`/api/v1/assessment-files/${props.fileId}/versions`); versions.value = (await res.json()).data || [] }
const isCurrent = (version) => version.version === versions.value[0]?.version
const restore = async (versionId) => { await fetch(`/api/v1/assessment-files/${props.fileId}/restore/${versionId}`, { method: 'POST' }); loadVersions() }
const formatDate = (date) => new Date(date).toLocaleString('vi-VN')
onMounted(loadVersions)
</script>

<style scoped>
.version-history { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.version-timeline { margin-top: 20px; }
.version-item { display: flex; gap: 16px; padding: 12px 0; border-bottom: 1px solid var(--border-color); }
.version-badge { min-width: 50px; padding: 4px 8px; background: var(--primary-600); color: white; border-radius: 20px; text-align: center; font-size: 12px; font-weight: 600; }
.version-info { flex: 1; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.version-date { font-size: 13px; color: var(--text-secondary); }
.version-user { font-size: 13px; }
.restore-btn { margin-left: auto; background: none; border: 1px solid var(--border-color); padding: 4px 12px; border-radius: 6px; cursor: pointer; transition: all 0.2s; }
.restore-btn:hover { background: var(--primary-600); color: white; border-color: var(--primary-600); }
</style>