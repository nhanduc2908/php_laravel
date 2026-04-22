<template>
    <div class="files-management">
        <div class="header-actions"><h2>Tệp đánh giá</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Tạo tệp mới</button></div>
        
        <div class="files-grid">
            <div v-for="file in files" :key="file.id" class="file-card">
                <div class="file-icon"><i :class="getFileIcon(file.file_type)"></i></div>
                <div class="file-info"><h3>{{ file.title }}</h3><p><i class="fas fa-server"></i> {{ file.server_name }}</p><p>v{{ file.version }} | {{ file.status }}</p></div>
                <div class="file-actions"><button @click="viewFile(file.id)"><i class="fas fa-eye"></i></button><button @click="editFile(file.id)" v-if="file.created_by === currentUser.id"><i class="fas fa-edit"></i></button><button @click="shareFile(file.id)"><i class="fas fa-share-alt"></i></button></div>
            </div>
        </div>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchFiles" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useFileStore } from '@/stores/file'
const fileStore = useFileStore()
const files = ref([]); const currentPage = ref(1); const totalPages = ref(1)
const getFileIcon = (type) => ({ document: 'fas fa-file-alt', spreadsheet: 'fas fa-file-excel', pdf: 'fas fa-file-pdf' }[type] || 'fas fa-file')
const fetchFiles = async () => { const res = await fileStore.fetch({ page: currentPage.value }); files.value = res.data; totalPages.value = res.last_page }
const viewFile = (id) => router.push(`/files/${id}`)
const editFile = (id) => router.push(`/files/${id}/edit`)
const shareFile = (id) => { /* Share modal */ }
onMounted(fetchFiles)
</script>