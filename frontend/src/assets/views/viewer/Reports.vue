<template>
    <div class="reports"><h2>Báo cáo an ninh</h2><div class="filters"><input type="text" v-model="search" placeholder="Tìm kiếm..."><select v-model="serverFilter"><option value="">Tất cả máy chủ</option><option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }}</option></select></div>
        <table class="data-table"><thead><tr><th>Máy chủ</th><th>Điểm</th><th>Tuân thủ</th><th>Ngày</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="r in reports" :key="r.id"><td>{{ r.server_name }}</td><td>{{ r.total_score }}%</td><td>{{ r.compliance_percent }}%</td><td>{{ formatDate(r.created_at) }}</td>
        <td><button @click="viewReport(r.id)"><i class="fas fa-eye"></i></button><button @click="downloadReport(r.id)"><i class="fas fa-download"></i></button></td></tr></tbody>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchReports" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'; import { useServerStore } from '@/stores/server'
import Pagination from '@/components/common/Pagination.vue'
const reportStore = useReportStore(); const serverStore = useServerStore()
const reports = ref([]); const servers = ref([]); const search = ref(''); const serverFilter = ref(''); const currentPage = ref(1); const totalPages = ref(1)
const formatDate = (date) => new Date(date).toLocaleDateString('vi-VN')
const fetchReports = async () => { const res = await reportStore.fetch({ search: search.value, server_id: serverFilter.value, page: currentPage.value }); reports.value = res.data; totalPages.value = res.last_page }
const viewReport = (id) => router.push(`/reports/${id}`); const downloadReport = async (id) => await reportStore.download(id)
onMounted(async () => { servers.value = await serverStore.fetchAll(); fetchReports() })
</script>