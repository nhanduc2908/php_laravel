<template>
    <div class="alerts"><h2>Cảnh báo an ninh</h2>
        <div class="alert-list"><div v-for="alert in alerts" :key="alert.id" :class="['alert-item', alert.severity, { unread: !alert.is_read }]"><div class="alert-icon"><i :class="getAlertIcon(alert.type)"></i></div><div class="alert-content"><h4>{{ alert.title }}</h4><p>{{ alert.message }}</p><small>{{ formatDate(alert.created_at) }}</small></div><div class="alert-actions"><button @click="markRead(alert.id)" v-if="!alert.is_read"><i class="fas fa-check"></i></button></div></div></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAlertStore } from '@/stores/alert'
const alertStore = useAlertStore()
const alerts = ref([])
const markRead = async (id) => { await alertStore.markRead(id); fetchAlerts() }
const fetchAlerts = async () => { alerts.value = await alertStore.fetch() }
onMounted(fetchAlerts)
</script>