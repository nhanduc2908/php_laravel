<template>
    <div class="assessments"><div class="header-actions"><h2>Đánh giá an ninh</h2><button class="btn-primary" @click="runNewAssessment"><i class="fas fa-play"></i> Chạy đánh giá mới</button></div>
    <div class="filters"><select v-model="serverFilter"><option value="">Tất cả máy chủ</option><option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }}</option></select></div>
    <table class="data-table"><thead><tr><th>Máy chủ</th><th>Điểm</th><th>Tuân thủ</th><th>Ngày</th><th>Thao tác</th></tr></thead>
    <tbody><tr v-for="a in assessments" :key="a.id"><td>{{ a.server_name }}</td><td class="score">{{ a.total_score }}%</td><td>{{ a.compliance_percent }}%</td><td>{{ formatDate(a.created_at) }}</td>
    <td class="actions"><button @click="viewDetail(a.id)"><i class="fas fa-eye"></i></button><button @click="exportReport(a.id)"><i class="fas fa-download"></i></button></td></tr></tbody>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchAssessments" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAssessmentStore } from '@/stores/assessment'
import { useServerStore } from '@/stores/server'
import Pagination from '@/components/common/Pagination.vue'
const assessmentStore = useAssessmentStore(); const serverStore = useServerStore()
const assessments = ref([]); const servers = ref([]); const serverFilter = ref(''); const currentPage = ref(1); const totalPages = ref(1)
const formatDate = (date) => new Date(date).toLocaleDateString('vi-VN')
const fetchAssessments = async () => { const res = await assessmentStore.fetch({ server_id: serverFilter.value, page: currentPage.value }); assessments.value = res.data; totalPages.value = res.last_page }
const runNewAssessment = () => router.push('/assessments/run'); const viewDetail = (id) => router.push(`/assessments/${id}`); const exportReport = async (id) => await assessmentStore.export(id)
onMounted(async () => { servers.value = await serverStore.fetchAll(); fetchAssessments() })
</script>