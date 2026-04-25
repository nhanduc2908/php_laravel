<template>
    <div class="files-management"><div class="header-actions"><h2>Tệp đánh giá</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Tạo tệp</button></div>
    <div class="files-list"><div v-for="f in files" :key="f.id" class="file-item"><i class="fas fa-file"></i> {{ f.title }} - {{ f.server_name }} - v{{ f.version }}<div class="file-actions"><button @click="viewFile(f.id)"><i class="fas fa-eye"></i></button><button @click="editFile(f.id)"><i class="fas fa-edit"></i></button><button @click="shareFile(f.id)"><i class="fas fa-share"></i></button></div></div></div>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchFiles" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useFileStore } from '@/stores/file'
import Pagination from '@/components/common/Pagination.vue'
const fileStore = useFileStore(); const files = ref([]); const currentPage = ref(1); const totalPages = ref(1)
const fetchFiles = async () => { const res = await fileStore.fetch({ page: currentPage.value }); files.value = res.data; totalPages.value = res.last_page }
const viewFile = (id) => router.push(`/files/${id}`); const editFile = (id) => router.push(`/files/${id}/edit`)
onMounted(fetchFiles)
</script>