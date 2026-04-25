<template>
    <div class="reports"><div class="header-actions"><h2>Báo cáo</h2><button class="btn-primary" @click="generateReport"><i class="fas fa-plus"></i> Tạo báo cáo</button></div>
    <table class="data-table"><thead><tr><th>Tên</th><th>Định dạng</th><th>Ngày</th><th>Thao tác</th></tr></thead>
    <tbody><tr v-for="r in reports" :key="r.id"><td>{{ r.name }}</td><td>{{ r.format }}</td><td>{{ formatDate(r.created_at) }}</td><td class="actions"><button @click="downloadReport(r.id)"><i class="fas fa-download"></i></button></td></tr></tbody>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchReports" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import Pagination from '@/components/common/Pagination.vue'
const reportStore = useReportStore(); const reports = ref([]); const currentPage = ref(1); const totalPages = ref(1)
const formatDate = (date) => new Date(date).toLocaleDateString('vi-VN')
const fetchReports = async () => { const res = await reportStore.fetch({ page: currentPage.value }); reports.value = res.data; totalPages.value = res.last_page }
const generateReport = () => router.push('/reports/generate'); const downloadReport = async (id) => await reportStore.download(id)
onMounted(fetchReports)
</script>
