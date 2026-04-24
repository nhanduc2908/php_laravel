<template>
    <div class="file-upload">
        <div class="upload-zone" :class="{ dragover: isDragover }" @dragover.prevent="isDragover = true" @dragleave.prevent="isDragover = false" @drop.prevent="handleDrop" @click="triggerFileInput">
            <input type="file" ref="fileInput" :accept="accept" :multiple="multiple" :disabled="disabled" style="display: none" @change="handleFileSelect">
            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="upload-text">Kéo thả file vào đây hoặc <span class="browse-link">chọn file</span></div>
            <div class="upload-hint">{{ hintText }}</div>
        </div>
        <div class="file-list" v-if="files.length > 0"><div v-for="(file, index) in files" :key="index" class="file-item"><i :class="getFileIcon(file.type)"></i><span class="file-name">{{ file.name }}</span><span class="file-size">{{ formatSize(file.size) }}</span><button class="remove-file" @click.stop="removeFile(index)"><i class="fas fa-times"></i></button></div></div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({ accept: { type: String, default: '*' }, multiple: Boolean, disabled: Boolean, maxSize: { type: Number, default: 10485760 }, hintText: { type: String, default: 'Tối đa 10MB' } })
const emit = defineEmits(['update:modelValue', 'files-selected'])
const fileInput = ref(null); const files = ref([]); const isDragover = ref(false)
const getFileIcon = (type) => { if (type.includes('pdf')) return 'fas fa-file-pdf'; if (type.includes('image')) return 'fas fa-file-image'; if (type.includes('word')) return 'fas fa-file-word'; if (type.includes('excel')) return 'fas fa-file-excel'; return 'fas fa-file' }
const formatSize = (bytes) => { if (bytes === 0) return '0 B'; const k = 1024; const sizes = ['B', 'KB', 'MB', 'GB']; const i = Math.floor(Math.log(bytes) / Math.log(k)); return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i] }
const triggerFileInput = () => { if (!props.disabled) fileInput.value?.click() }
const handleFileSelect = (e) => { processFiles(Array.from(e.target.files)) }
const handleDrop = (e) => { isDragover.value = false; processFiles(Array.from(e.dataTransfer.files)) }
const processFiles = (newFiles) => { const validFiles = newFiles.filter(f => f.size <= props.maxSize); files.value = props.multiple ? [...files.value, ...validFiles] : validFiles; emit('update:modelValue', files.value); emit('files-selected', files.value) }
const removeFile = (index) => { files.value.splice(index, 1); emit('update:modelValue', files.value); emit('files-selected', files.value) }
defineExpose({ clear: () => { files.value = []; if (fileInput.value) fileInput.value.value = '' } })
</script>

<style scoped>
.upload-zone { border: 2px dashed var(--border-color); border-radius: 12px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.2s; }
.upload-zone:hover, .upload-zone.dragover { border-color: var(--primary-600); background: var(--bg-secondary); }
.upload-icon { font-size: 3rem; color: var(--text-secondary); margin-bottom: 12px; }
.browse-link { color: var(--primary-600); cursor: pointer; }
.upload-hint { font-size: 0.75rem; color: var(--text-secondary); margin-top: 8px; }
.file-list { margin-top: 16px; }
.file-item { display: flex; align-items: center; gap: 12px; padding: 8px 12px; background: var(--bg-secondary); border-radius: 8px; margin-bottom: 8px; }
.file-item i { font-size: 1.25rem; }
.file-name { flex: 1; }
.file-size { font-size: 0.75rem; color: var(--text-secondary); }
.remove-file { background: none; border: none; cursor: pointer; color: var(--danger); }
</style>