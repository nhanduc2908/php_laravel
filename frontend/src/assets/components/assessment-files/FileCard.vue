
<template>
    <div class="file-card" @click="$emit('click', file)">
        <div class="card-icon" :class="fileTypeClass"><i :class="fileIcon"></i></div>
        <div class="card-content"><h4 class="card-title">{{ file.title }}</h4><p class="card-meta"><span><i class="fas fa-server"></i> {{ file.server_name }}</span><span><i class="fas fa-user"></i> {{ file.created_by_name }}</span></p><p class="card-meta"><span><i class="fas fa-tag"></i> v{{ file.version }}</span><span :class="`status-badge ${file.status}`">{{ file.status === 'draft' ? 'Nháp' : (file.status === 'published' ? 'Đã xuất bản' : 'Lưu trữ') }}</span></p></div>
        <div class="card-actions"><button @click.stop="$emit('edit', file)" title="Sửa"><i class="fas fa-edit"></i></button><button @click.stop="$emit('share', file)" title="Chia sẻ"><i class="fas fa-share-alt"></i></button><button @click.stop="$emit('delete', file)" title="Xóa" class="danger"><i class="fas fa-trash"></i></button></div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ file: { type: Object, required: true } })
defineEmits(['click', 'edit', 'share', 'delete'])

const fileTypeClass = computed(() => ({ document: 'doc', spreadsheet: 'xls', pdf: 'pdf', image: 'img' }[props.file.file_type] || 'default'))
const fileIcon = computed(() => ({ document: 'fas fa-file-alt', spreadsheet: 'fas fa-file-excel', pdf: 'fas fa-file-pdf', image: 'fas fa-file-image' }[props.file.file_type] || 'fas fa-file'))
</script>

<style scoped>
.file-card { display: flex; align-items: center; gap: 16px; background: var(--card-bg); border-radius: 12px; padding: 16px; cursor: pointer; transition: all 0.2s; border: 1px solid var(--border-color); }
.file-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.card-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
.card-icon.doc { background: #dbeafe; color: #3b82f6; }
.card-icon.xls { background: #d1fae5; color: #10b981; }
.card-icon.pdf { background: #fee2e2; color: #ef4444; }
.card-icon.img { background: #fef3c7; color: #f59e0b; }
.card-icon.default { background: var(--bg-secondary); color: var(--text-secondary); }
.card-content { flex: 1; }
.card-title { margin: 0 0 8px 0; font-size: 16px; font-weight: 500; }
.card-meta { display: flex; gap: 16px; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px; }
.status-badge { padding: 2px 8px; border-radius: 12px; font-size: 11px; }
.status-badge.draft { background: #fef3c7; color: #f59e0b; }
.status-badge.published { background: #d1fae5; color: #10b981; }
.status-badge.archived { background: #fee2e2; color: #ef4444; }
.card-actions { display: flex; gap: 8px; }
.card-actions button { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.card-actions button:hover { background: var(--bg-secondary); color: var(--primary-600); }
.card-actions button.danger:hover { color: var(--danger); }
</style>