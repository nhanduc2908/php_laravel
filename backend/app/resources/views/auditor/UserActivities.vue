<template>
    <div class="user-activities"><h2>Hoạt động người dùng</h2>
        <div class="filters"><select v-model="userId"><option value="">Chọn người dùng</option><option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }} ({{ u.email }})</option></select></div>
        <table class="data-table"><thead><tr><th>Thời gian</th><th>Hành động</th><th>Tài nguyên</th><th>IP</th></tr></thead>
        <tbody><tr v-for="act in activities" :key="act.id"><td>{{ formatDateTime(act.created_at) }}</td><td>{{ act.action }}</td><td>{{ act.resource }}</td><td>{{ act.ip }}</td></tr></tbody></table>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useAuditStore } from '@/stores/audit'
import { useUserStore } from '@/stores/user'
const auditStore = useAuditStore(); const userStore = useUserStore()
const userId = ref(''); const users = ref([]); const activities = ref([])
watch(userId, async (newId) => { if (newId) activities.value = await auditStore.getUserActivities(newId) })
onMounted(async () => { users.value = await userStore.fetchAll() })
</script>