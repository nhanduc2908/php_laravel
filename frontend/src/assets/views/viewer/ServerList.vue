<template>
    <div class="server-list"><h2>Danh sách máy chủ</h2>
        <table class="data-table"><thead><tr><th>Tên máy chủ</th><th>IP</th><th>Điểm</th><th>Lần đánh giá cuối</th></tr></thead>
        <tbody><tr v-for="s in servers" :key="s.id"><td>{{ s.name }}</td><td>{{ s.host }}</td><td>{{ s.score }}%</td><td>{{ formatDate(s.last_assessment) }}</td></tr></tbody>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchServers" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useServerStore } from '@/stores/server'
import Pagination from '@/components/common/Pagination.vue'
const serverStore = useServerStore(); const servers = ref([]); const currentPage = ref(1); const totalPages = ref(1)
const formatDate = (date) => date ? new Date(date).toLocaleDateString('vi-VN') : 'Chưa đánh giá'
const fetchServers = async () => { const res = await serverStore.fetch({ page: currentPage.value }); servers.value = res.data; totalPages.value = res.last_page }
onMounted(fetchServers)
</script>