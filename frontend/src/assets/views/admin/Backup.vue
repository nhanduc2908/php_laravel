<template>
    <div class="backup"><div class="header-actions"><h2>Sao lưu</h2><button class="btn-primary" @click="createBackup"><i class="fas fa-database"></i> Tạo backup</button></div>
    <table class="data-table"><thead><tr><th>File</th><th>Kích thước</th><th>Ngày</th><th>Thao tác</th></tr></thead>
    <tbody><tr v-for="b in backups" :key="b.id"><td>{{ b.filename }}</td><td>{{ formatSize(b.size) }}</td><td>{{ formatDate(b.created_at) }}</td><td class="actions"><button @click="restoreBackup(b.id)"><i class="fas fa-undo"></i></button><button @click="downloadBackup(b.id)"><i class="fas fa-download"></i></button></td></tr></tbody>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchBackups" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useBackupStore } from '@/stores/backup'
import Pagination from '@/components/common/Pagination.vue'
const backupStore = useBackupStore(); const backups = ref([]); const currentPage = ref(1); const totalPages = ref(1)
const formatDate = (date) => new Date(date).toLocaleDateString('vi-VN'); const formatSize = (bytes) => { if (!bytes) return '0 B'; const k = 1024; const sizes = ['B', 'KB', 'MB']; const i = Math.floor(Math.log(bytes) / Math.log(k)); return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i] }
const fetchBackups = async () => { const res = await backupStore.fetch({ page: currentPage.value }); backups.value = res.data; totalPages.value = res.last_page }
const createBackup = async () => { await backupStore.create(); fetchBackups() }; const restoreBackup = async (id) => { if (confirm('Phục hồi backup này?')) await backupStore.restore(id) }; const downloadBackup = async (id) => await backupStore.download(id)
onMounted(fetchBackups)
</script>