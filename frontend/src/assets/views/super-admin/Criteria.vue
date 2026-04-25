<template>
    <div class="criteria-management"><div class="header-actions"><h2>Quản lý tiêu chí (280 tiêu chí)</h2><div><button class="btn-secondary" @click="importCriteria"><i class="fas fa-upload"></i> Import Excel</button><button class="btn-secondary" @click="exportCriteria"><i class="fas fa-download"></i> Export Excel</button><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Thêm tiêu chí</button></div></div>
    <div class="filters"><select v-model="categoryFilter" @change="fetchCriteria"><option value="">Tất cả danh mục</option><option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option></select><input type="text" v-model="search" placeholder="Tìm kiếm..." @input="fetchCriteria"></div>
    <table class="data-table"><thead><tr><th>Mã</th><th>Tên tiêu chí</th><th>Danh mục</th><th>Trọng số</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody><tr v-for="criterion in criteria" :key="criterion.id"><td class="code">{{ criterion.code }}</td><td class="name">{{ criterion.name }}</td><td>{{ criterion.category_name }}</td><td class="weight">{{ criterion.weight }}</td><td><span :class="criterion.status === 'active' ? 'badge-active' : 'badge-inactive'">{{ criterion.status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}</span></td>
    <td class="actions"><button @click="editCriterion(criterion)"><i class="fas fa-edit"></i></button><button @click="deleteCriterion(criterion.id)"><i class="fas fa-trash"></i></button></td></tr></tbody></table>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchCriteria" /></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCriteriaStore } from '@/stores/criteria'; import { useCategoryStore } from '@/stores/category'; import Pagination from '@/components/common/Pagination.vue'
const criteriaStore = useCriteriaStore(); const categoryStore = useCategoryStore(); const criteria = ref([]); const categories = ref([]); const search = ref(''); const categoryFilter = ref(''); const currentPage = ref(1); const totalPages = ref(1)
const fetchCriteria = async () => { const res = await criteriaStore.fetch({ search: search.value, category_id: categoryFilter.value, page: currentPage.value }); criteria.value = res.data; totalPages.value = res.last_page }
const importCriteria = () => { /* File upload logic */ }; const exportCriteria = async () => { await criteriaStore.export() }
onMounted(async () => { categories.value = await categoryStore.fetchAll(); fetchCriteria() })
</script>