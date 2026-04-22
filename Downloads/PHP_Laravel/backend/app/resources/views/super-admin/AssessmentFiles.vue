<template>
    <div class="files-management">
        <div class="header-actions">
            <h2>Quản lý tệp đánh giá</h2>
            <button class="btn-primary" @click="openCreateModal">
                <i class="fas fa-plus"></i> Tạo tệp mới
            </button>
        </div>

        <div class="files-grid">
            <div v-for="file in files" :key="file.id" class="file-card">
                <div class="file-icon">
                    <i :class="getFileIcon(file.file_type)"></i>
                </div>
                <div class="file-info">
                    <h3>{{ file.title }}</h3>
                    <p><i class="fas fa-server"></i> {{ file.server_name }}</p>
                    <p><i class="fas fa-user"></i> {{ file.created_by_name }}</p>
                    <p><i class="fas fa-tag"></i> v{{ file.version }}</p>
                    <span :class="`status-${file.status}`">{{ file.status }}</span>
                </div>
                <div class="file-actions">
                    <button @click="viewFile(file.id)"><i class="fas fa-eye"></i></button>
                    <button @click="editFile(file.id)"><i class="fas fa-edit"></i></button>
                    <button @click="shareFile(file.id)"><i class="fas fa-share-alt"></i></button>
                    <button @click="downloadFile(file.id)"><i class="fas fa-download"></i></button>
                    <button @click="deleteFile(file.id)"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchFiles" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useFileStore } from '@/stores/file'

const fileStore = useFileStore()
const files = ref([])
const currentPage = ref(1)
const totalPages = ref(1)

const getFileIcon = (type) => {
    const icons = { document: 'fas fa-file-alt', spreadsheet: 'fas fa-file-excel', pdf: 'fas fa-file-pdf', image: 'fas fa-file-image' }
    return icons[type] || 'fas fa-file'
}

const fetchFiles = async () => {
    const res = await fileStore.fetch({ page: currentPage.value })
    files.value = res.data
    totalPages.value = res.last_page
}
const viewFile = (id) => router.push(`/files/${id}`)
const editFile = (id) => router.push(`/files/${id}/edit`)
const shareFile = (id) => { /* Open share modal */ }
const downloadFile = async (id) => await fileStore.download(id)
const deleteFile = async (id) => { if (confirm('Xóa?')) await fileStore.delete(id); fetchFiles() }

onMounted(fetchFiles)
</script>