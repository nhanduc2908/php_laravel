<template>
    <div class="upload-zone" :class="{ dragover: isDragover }" @dragover.prevent="isDragover = true" @dragleave.prevent="isDragover = false" @drop.prevent="handleDrop" @click="triggerFileInput">
        <input type="file" ref="fileInput" :accept="accept" :multiple="multiple" style="display: none" @change="handleFileSelect">
        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
        <div class="upload-text">Kéo thả file vào đây hoặc <span class="browse-link">chọn file</span></div>
        <div class="upload-hint">{{ hintText }}</div>
    </div>
    <div v-if="uploading" class="upload-progress"><div class="progress-bar"><div class="progress-fill" :style="{ width: `${progress}%` }"></div></div><span>{{ progress }}%</span></div>
    <div class="uploaded-files" v-if="uploadedFiles.length"><div v-for="file in uploadedFiles" :key="file.name" class="uploaded-file"><i class="fas fa-file"></i><span>{{ file.name }}</span><button class="remove-file" @click.stop="removeFile(file)"><i class="fas fa-times"></i></button></div></div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({ accept: { type: String, default: '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.png' }, multiple: Boolean, hintText: { type: String, default: 'Hỗ trợ PDF, Word, Excel, ảnh (tối đa 10MB)' } })
const emit = defineEmits(['uploaded'])
const fileInput = ref(null); const isDragover = ref(false); const uploading = ref(false); const progress = ref(0); const uploadedFiles = ref([])

const triggerFileInput = () => fileInput.value?.click()
const handleFileSelect = (e) => uploadFiles(Array.from(e.target.files))
const handleDrop = (e) => { isDragover.value = false; uploadFiles(Array.from(e.dataTransfer.files)) }
const uploadFiles = async (files) => { for (const file of files) { uploading.value = true; progress.value = 0; const formData = new FormData(); formData.append('file', file); const xhr = new XMLHttpRequest(); xhr.upload.onprogress = (e) => { if (e.lengthComputable) progress.value = Math.round((e.loaded / e.total) * 100) }; xhr.onload = () => { if (xhr.status === 200) { uploadedFiles.value.push(file); emit('uploaded', JSON.parse(xhr.response)) } uploading.value = false }; xhr.open('POST', '/api/v1/assessment-files/upload'); xhr.send(formData) } }
const removeFile = (file) => { uploadedFiles.value = uploadedFiles.value.filter(f => f !== file) }
</script>

<style scoped>
.upload-zone { border: 2px dashed var(--border-color); border-radius: 12px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.2s; }
.upload-zone:hover, .upload-zone.dragover { border-color: var(--primary-600); background: var(--bg-secondary); }
.upload-icon { font-size: 48px; color: var(--text-secondary); margin-bottom: 16px; }
.browse-link { color: var(--primary-600); }
.upload-hint { font-size: 12px; color: var(--text-secondary); margin-top: 8px; }
.upload-progress { display: flex; align-items: center; gap: 12px; margin-top: 16px; }
.progress-bar { flex: 1; background: var(--bg-secondary); border-radius: 10px; height: 8px; overflow: hidden; }
.progress-fill { background: var(--primary-600); height: 100%; transition: width 0.3s; }
.uploaded-files { margin-top: 16px; }
.uploaded-file { display: flex; align-items: center; gap: 12px; padding: 8px 12px; background: var(--bg-secondary); border-radius: 8px; margin-bottom: 8px; }
.remove-file { margin-left: auto; background: none; border: none; cursor: pointer; color: var(--danger); }
</style>