<template>
    <div class="servers-management"><div class="header-actions"><h2>Quản lý máy chủ</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Thêm máy chủ</button></div>
    <div class="servers-grid"><div v-for="server in servers" :key="server.id" class="server-card"><div class="server-status" :class="server.status"><i class="fas fa-circle"></i> {{ server.status }}</div><h3>{{ server.name }}</h3><p><i class="fas fa-server"></i> {{ server.host }}:{{ server.port }}</p><p><i class="fas fa-chart-line"></i> Điểm: {{ server.score }}%</p>
    <div class="card-actions"><button @click="testConnection(server.id)"><i class="fas fa-plug"></i> Test</button><button @click="scanServer(server.id)"><i class="fas fa-search"></i> Quét</button><button @click="editServer(server)"><i class="fas fa-edit"></i></button><button @click="deleteServer(server.id)"><i class="fas fa-trash"></i></button></div></div></div></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useServerStore } from '@/stores/server'
const serverStore = useServerStore(); const servers = ref([])
const testConnection = async (id) => { const result = await serverStore.testConnection(id); alert(result.success ? 'Kết nối thành công' : 'Kết nối thất bại') }
const scanServer = async (id) => { await serverStore.scan(id); alert('Đã bắt đầu quét') }
onMounted(async () => { servers.value = await serverStore.fetchServers() })
</script>