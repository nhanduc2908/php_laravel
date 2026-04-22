<template>
    <div class="servers-list">
        <h2>Danh sách máy chủ</h2>
        <div class="servers-grid">
            <div v-for="server in servers" :key="server.id" class="server-card">
                <div class="server-header"><h3>{{ server.name }}</h3><span :class="['status', server.status]">{{ server.status }}</span></div>
                <div class="server-info"><p><i class="fas fa-server"></i> {{ server.host }}:{{ server.port }}</p><p><i class="fas fa-chart-line"></i> Điểm: {{ server.score }}%</p></div>
                <div class="server-actions"><button @click="viewDetail(server.id)" class="btn-outline"><i class="fas fa-eye"></i> Chi tiết</button><button @click="scanServer(server.id)" class="btn-primary"><i class="fas fa-search"></i> Quét</button></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useServerStore } from '@/stores/server'
const serverStore = useServerStore()
const servers = ref([])
const viewDetail = (id) => router.push(`/servers/${id}`)
const scanServer = async (id) => { await serverStore.scan(id); alert('Đã bắt đầu quét') }
onMounted(async () => { servers.value = await serverStore.fetchServers() })
</script>