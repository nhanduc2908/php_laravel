<template>
    <div class="alerts"><h2>Cảnh báo</h2><div class="alert-list"><div v-for="a in alerts" :key="a.id" :class="['alert-item', a.severity, { unread: !a.is_read }]"><i :class="getIcon(a.type)"></i> {{ a.title }} - {{ a.message }}<button @click="markRead(a.id)"><i class="fas fa-check"></i></button></div></div>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchAlerts" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAlertStore } from '@/stores/alert'
import Pagination from '@/components/common/Pagination.vue'
const alertStore = useAlertStore(); const alerts = ref([]); const currentPage = ref(1); const totalPages = ref(1)
const getIcon = (type) => 'fas fa-bell'
const fetchAlerts = async () => { const res = await alertStore.fetch({ page: currentPage.value }); alerts.value = res.data; totalPages.value = res.last_page }
const markRead = async (id) => { await alertStore.markRead(id); fetchAlerts() }
onMounted(fetchAlerts)
</script>