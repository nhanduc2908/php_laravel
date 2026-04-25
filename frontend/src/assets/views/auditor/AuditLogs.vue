<template>
    <div class="audit-logs"><h2>Audit Logs</h2><div class="filters"><input type="text" v-model="search" placeholder="Tìm kiếm..."><select v-model="actionFilter"><option value="">Tất cả hành động</option><option>login</option><option>logout</option><option>create</option><option>update</option><option>delete</option></select><input type="date" v-model="dateFrom"><input type="date" v-model="dateTo"><button class="btn-secondary" @click="exportLogs"><i class="fas fa-download"></i> Xuất</button></div>
        <table class="data-table"><thead><tr><th>Thời gian</th><th>Người dùng</th><th>Hành động</th><th>Tài nguyên</th><th>IP</th><th>User Agent</th></tr></thead>
        <tbody><tr v-for="log in logs" :key="log.id"><td>{{ formatDateTime(log.created_at) }}</td><td>{{ log.user_name }}</td><td>{{ log.action }}</td><td>{{ log.resource }}:{{ log.resource_id }}</td><td>{{ log.ip }}</td><td>{{ truncate(log.user_agent, 30) }}</td></tr></tbody>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchLogs" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuditStore } from '@/stores/audit'
import Pagination from '@/components/common/Pagination.vue'
const auditStore = useAuditStore(); const logs = ref([]); const search = ref(''); const actionFilter = ref(''); const dateFrom = ref(''); const dateTo = ref(''); const currentPage = ref(1); const totalPages = ref(1)
const formatDateTime = (date) => new Date(date).toLocaleString('vi-VN'); const truncate = (str, len) => str?.length > len ? str.substring(0, len) + '...' : str || ''
const fetchLogs = async () => { const res = await auditStore.fetch({ search: search.value, action: actionFilter.value, from: dateFrom.value, to: dateTo.value, page: currentPage.value }); logs.value = res.data; totalPages.value = res.last_page }
const exportLogs = async () => { await auditStore.export({ search: search.value, action: actionFilter.value, from: dateFrom.value, to: dateTo.value }) }
onMounted(fetchLogs)
</script>