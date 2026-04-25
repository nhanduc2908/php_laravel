<template>
    <div class="vulnerabilities"><h2>Lỗ hổng bảo mật</h2><div class="filters"><select v-model="severityFilter"><option value="">Tất cả</option><option value="critical">Critical</option><option value="high">High</option><option value="medium">Medium</option><option value="low">Low</option></select></div>
    <table class="data-table"><thead><tr><th>CVE</th><th>Tên</th><th>Mức độ</th><th>Trạng thái</th></tr></thead>
    <tbody><tr v-for="v in vulnerabilities" :key="v.id"><td>{{ v.cve }}</td><td class="name">{{ v.name }}</td><td><span :class="`severity-${v.severity}`">{{ v.severity }}</span></td><td>{{ v.status }}</td></tr></tbody>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchVulnerabilities" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useVulnerabilityStore } from '@/stores/vulnerability'
import Pagination from '@/components/common/Pagination.vue'
const vulnStore = useVulnerabilityStore(); const vulnerabilities = ref([]); const severityFilter = ref(''); const currentPage = ref(1); const totalPages = ref(1)
const fetchVulnerabilities = async () => { const res = await vulnStore.fetch({ severity: severityFilter.value, page: currentPage.value }); vulnerabilities.value = res.data; totalPages.value = res.last_page }
onMounted(fetchVulnerabilities)
</script>