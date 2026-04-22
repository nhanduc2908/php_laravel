<template>
    <div class="vulnerabilities">
        <h2>Quản lý lỗ hổng</h2>
        <div class="filters"><select v-model="severityFilter"><option value="">Tất cả</option><option value="critical">Critical</option><option value="high">High</option><option value="medium">Medium</option><option value="low">Low</option></select><select v-model="statusFilter"><option value="">Tất cả</option><option value="open">Chưa xử lý</option><option value="fixed">Đã xử lý</option></select></div>
        <table class="data-table"><thead><tr><th>CVE</th><th>Tên</th><th>Máy chủ</th><th>Mức độ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="v in vulnerabilities" :key="v.id"><td class="cve">{{ v.cve || 'N/A' }}</td><td>{{ v.name }}</td><td>{{ v.server_name }}</td><td><span :class="`severity-${v.severity}`">{{ v.severity }}</span></td><td>{{ v.status }}</td><td><button @click="markFixed(v.id)" v-if="v.status === 'open'"><i class="fas fa-check"></i> Xử lý</button><button @click="viewDetail(v.id)"><i class="fas fa-eye"></i></button></td></tr></tbody></table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useVulnerabilityStore } from '@/stores/vulnerability'
const vulnStore = useVulnerabilityStore()
const vulnerabilities = ref([]); const severityFilter = ref(''); const statusFilter = ref('')
const markFixed = async (id) => { await vulnStore.markFixed(id); fetchVulnerabilities() }
const fetchVulnerabilities = async () => { vulnerabilities.value = await vulnStore.fetch({ severity: severityFilter.value, status: statusFilter.value }) }
onMounted(fetchVulnerabilities)
</script>