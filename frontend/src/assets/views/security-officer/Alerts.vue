<template>
    <div class="alerts"><h2>Cảnh báo an ninh</h2><div class="alert-list"><div v-for="alert in alerts" :key="alert.id" :class="['alert-item', alert.severity, { unread: !alert.is_read }]"><div class="alert-icon"><i :class="getAlertIcon(alert.type)"></i></div><div class="alert-content"><h4>{{ alert.title }}</h4><p>{{ alert.message }}</p><small>{{ formatDate(alert.created_at) }}</small></div>
    <div class="alert-actions"><button @click="markRead(alert.id)" v-if="!alert.is_read"><i class="fas fa-check"></i></button></div></div></div>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchAlerts" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAlertStore } from '@/stores/alert'
import Pagination from '@/components/common/Pagination.vue'
const alertStore = useAlertStore(); const alerts = ref([]); const currentPage = ref(1); const totalPages = ref(1)
const getAlertIcon = (type) => ({ vulnerability: 'fas fa-bug', assessment: 'fas fa-clipboard-list' }[type] || 'fas fa-bell')
const formatDate = (date) => new Date(date).toLocaleString('vi-VN')
const fetchAlerts = async () => { const res = await alertStore.fetch({ page: currentPage.value }); alerts.value = res.data; totalPages.value = res.last_page }
const markRead = async (id) => { await alertStore.markRead(id); fetchAlerts() }
onMounted(fetchAlerts)
</script>