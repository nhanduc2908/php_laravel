<template>
    <div class="criteria-management"><div class="header-actions"><h2>Quản lý tiêu chí (280)</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Thêm</button><button class="btn-secondary" @click="importCriteria"><i class="fas fa-upload"></i> Import</button></div>
    <div class="filters"><select v-model="categoryFilter"><option value="">Tất cả</option><option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option></select><input type="text" v-model="search" placeholder="Tìm kiếm..."></div>
    <table class="data-table"><thead><tr><th>Mã</th><th>Tên</th><th>Trọng số</th><th>Thao tác</th></tr></thead>
    <tbody><tr v-for="c in criteria" :key="c.id"><td>{{ c.code }}</td><td class="name">{{ c.name }}</td><td class="weight">{{ c.weight }}</td><td class="actions"><button @click="editCriterion(c)"><i class="fas fa-edit"></i></button><button @click="deleteCriterion(c.id)"><i class="fas fa-trash"></i></button></td></tr></tbody>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchCriteria" />
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCriteriaStore } from '@/stores/criteria'
import { useCategoryStore } from '@/stores/category'
import Pagination from '@/components/common/Pagination.vue'
const criteriaStore = useCriteriaStore(); const categoryStore = useCategoryStore()
const criteria = ref([]); const categories = ref([]); const search = ref(''); const categoryFilter = ref(''); const currentPage = ref(1); const totalPages = ref(1)
const fetchCriteria = async () => { const res = await criteriaStore.fetch({ search: search.value, category_id: categoryFilter.value, page: currentPage.value }); criteria.value = res.data; totalPages.value = res.last_page }
const importCriteria = () => { /* File upload */ }
onMounted(async () => { categories.value = await categoryStore.fetchAll(); fetchCriteria() })
</script>