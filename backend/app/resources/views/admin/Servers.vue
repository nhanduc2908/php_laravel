<template>
    <div class="servers-management"><div class="header-actions"><h2>Quản lý máy chủ</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Thêm máy chủ</button></div>
        <div class="servers-grid"><div v-for="server in servers" :key="server.id" class="server-card"><h3>{{ server.name }}</h3><p>{{ server.host }}:{{ server.port }}</p><p>Điểm: {{ server.score }}%</p>
        <div class="card-actions"><button @click="testConnection(server.id)"><i class="fas fa-plug"></i></button><button @click="scanServer(server.id)"><i class="fas fa-search"></i></button><button @click="editServer(server)"><i class="fas fa-edit"></i></button><button @click="deleteServer(server.id)"><i class="fas fa-trash"></i></button></div></div></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useServerStore } from '@/stores/server'
const serverStore = useServerStore()
const servers = ref([])
const testConnection = async (id) => { const result = await serverStore.testConnection(id); alert(result.success ? 'OK' : 'Failed') }
const scanServer = async (id) => { await serverStore.scan(id); alert('Đã bắt đầu quét') }
onMounted(async () => { servers.value = await serverStore.fetchServers() })
</script>