<template>
    <div class="file-preview"><div class="preview-header"><h3>{{ file.title }}</h3><button class="close-btn" @click="close"><i class="fas fa-times"></i></button></div>
    <div class="preview-meta"><div class="meta-item"><i class="fas fa-server"></i> {{ file.server_name }}</div><div class="meta-item"><i class="fas fa-user"></i> {{ file.created_by_name }}</div><div class="meta-item"><i class="fas fa-tag"></i> v{{ file.version }}</div><div class="meta-item"><span class="status-badge" :class="file.status">{{ file.status }}</span></div></div>
    <div class="preview-content" v-html="renderedContent"></div>
    <div class="preview-attachments" v-if="attachments.length"><h4>File đính kèm</h4><div class="attachment-list"><div v-for="att in attachments" :key="att.id" class="attachment-item"><i class="fas fa-paperclip"></i><a :href="att.url" target="_blank">{{ att.name }}</a><span>{{ formatSize(att.size) }}</span></div></div></div>
    <div class="preview-versions" v-if="versions.length"><h4>Lịch sử phiên bản</h4><div class="version-list"><div v-for="ver in versions" :key="ver.id" class="version-item"><span class="version-num">v{{ ver.version }}</span><span class="version-date">{{ formatDate(ver.created_at) }}</span><span class="version-user">{{ ver.created_by_name }}</span></div></div></div>
    <div class="preview-actions"><button class="btn btn-primary" @click="download"><i class="fas fa-download"></i> Tải xuống</button><button class="btn btn-secondary" @click="edit"><i class="fas fa-edit"></i> Chỉnh sửa</button></div>
</div></template>

<script setup>
import { ref, computed } from 'vue'
import DOMPurify from 'dompurify'

const props = defineProps({ file: { type: Object, required: true } })
const emit = defineEmits(['close', 'edit', 'download'])
const attachments = ref([])
const versions = ref([])

const renderedContent = computed(() => DOMPurify.sanitize(props.file.content))
const formatDate = (date) => new Date(date).toLocaleString('vi-VN')
const formatSize = (bytes) => { if (!bytes) return '0 B'; const k = 1024; const sizes = ['B', 'KB', 'MB']; const i = Math.floor(Math.log(bytes) / Math.log(k)); return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i] }
const close = () => emit('close')
const edit = () => emit('edit')
const download = () => emit('download')
</script>

<style scoped>
.file-preview { background: var(--card-bg); border-radius: 12px; max-width: 900px; width: 90vw; max-height: 85vh; overflow-y: auto; }
.preview-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border-color); position: sticky; top: 0; background: inherit; }
.preview-meta { display: flex; gap: 16px; padding: 12px 20px; background: var(--bg-secondary); flex-wrap: wrap; }
.status-badge { padding: 4px 8px; border-radius: 20px; font-size: 12px; }
.status-badge.draft { background: #fef3c7; color: #f59e0b; }
.status-badge.published { background: #d1fae5; color: #10b981; }
.preview-content { padding: 20px; }
.preview-attachments, .preview-versions { padding: 16px 20px; border-top: 1px solid var(--border-color); }
.attachment-list, .version-list { margin-top: 12px; }
.attachment-item, .version-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid var(--border-color); }
.preview-actions { display: flex; justify-content: flex-end; gap: 12px; padding: 16px 20px; border-top: 1px solid var(--border-color); }
</style>