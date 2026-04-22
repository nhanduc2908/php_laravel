<template>
    <div class="assessments-management">
        <div class="header-actions">
            <h2>Quản lý đánh giá an ninh</h2>
            <button class="btn-primary" @click="runNewAssessment">
                <i class="fas fa-play"></i> Chạy đánh giá mới
            </button>
        </div>

        <div class="filters">
            <select v-model="serverFilter" @change="fetchAssessments">
                <option value="">Tất cả máy chủ</option>
                <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }}</option>
            </select>
            <input type="date" v-model="dateFrom" @change="fetchAssessments">
            <input type="date" v-model="dateTo" @change="fetchAssessments">
        </div>

        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Máy chủ</th><th>Điểm</th><th>Tuân thủ</th><th>Trạng thái</th><th>Ngày</th><th>Thao tác</th></tr>
            </thead>
            <tbody>
                <tr v-for="assessment in assessments" :key="assessment.id">
                    <td>{{ assessment.id }}</td>
                    <td>{{ assessment.server_name }}</td>
                    <td class="score">{{ assessment.total_score }}%</td>
                    <td>{{ assessment.compliance_percent }}%</td>
                    <td><span :class="assessment.status === 'compliant' ? 'badge-success' : 'badge-danger'">
                        {{ assessment.status === 'compliant' ? 'Đạt' : 'Không đạt' }}
                    </span></td>
                    <td>{{ formatDate(assessment.created_at) }}</td>
                    <td class="actions">
                        <button @click="viewDetail(assessment.id)"><i class="fas fa-eye"></i></button>
                        <button @click="exportReport(assessment.id)"><i class="fas fa-download"></i></button>
                        <button @click="deleteAssessment(assessment.id)"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchAssessments" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAssessmentStore } from '@/stores/assessment'
import { useServerStore } from '@/stores/server'

const assessmentStore = useAssessmentStore()
const serverStore = useServerStore()
const assessments = ref([])
const servers = ref([])
const serverFilter = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const currentPage = ref(1)
const totalPages = ref(1)

const formatDate = (date) => new Date(date).toLocaleDateString('vi-VN')
const fetchAssessments = async () => {
    const res = await assessmentStore.fetch({ server_id: serverFilter.value, from: dateFrom.value, to: dateTo.value, page: currentPage.value })
    assessments.value = res.data
    totalPages.value = res.last_page
}
const runNewAssessment = () => router.push('/assessments/run')
const viewDetail = (id) => router.push(`/assessments/${id}`)
const exportReport = async (id) => await assessmentStore.export(id)
const deleteAssessment = async (id) => { if (confirm('Xóa?')) await assessmentStore.delete(id); fetchAssessments() }

onMounted(async () => {
    servers.value = await serverStore.fetchAll()
    fetchAssessments()
})
</script>