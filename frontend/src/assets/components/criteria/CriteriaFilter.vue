<template>
    <div class="criteria-filter">
        <div class="filter-group">
            <div class="filter-item"><label>Danh mục</label>
                <select v-model="filters.category_id" @change="applyFilter">
                    <option value="">Tất cả danh mục</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
            </div>
            <div class="filter-item"><label>Trạng thái</label>
                <select v-model="filters.status" @change="applyFilter"><option value="">Tất cả</option><option value="active">Hoạt động</option><option value="inactive">Không hoạt động</option></select>
            </div>
            <div class="filter-item"><label>Trọng số từ</label><input type="number" v-model="filters.weight_min" @input="applyFilter" min="1" max="10"></div>
            <div class="filter-item"><label>đến</label><input type="number" v-model="filters.weight_max" @input="applyFilter" min="1" max="10"></div>
            <div class="filter-item"><label class="checkbox-label"><input type="checkbox" v-model="filters.has_evidence" @change="applyFilter"> Có bằng chứng</label></div>
        </div>
        <div class="filter-actions"><button class="btn btn-secondary btn-sm" @click="resetFilters">Đặt lại</button></div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

const emit = defineEmits(['filter-change'])

const categories = ref([])
const filters = reactive({ category_id: '', status: '', weight_min: '', weight_max: '', has_evidence: false })

const fetchCategories = async () => { const response = await fetch('/api/v1/categories'); categories.value = (await response.json()).data || [] }
const applyFilter = () => emit('filter-change', { ...filters })
const resetFilters = () => { Object.keys(filters).forEach(k => filters[k] = ''); filters.has_evidence = false; applyFilter() }

onMounted(fetchCategories)
</script>

<style scoped>
.criteria-filter { background: var(--card-bg); border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; }
.filter-group { display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end; }
.filter-item { display: flex; flex-direction: column; gap: 6px; }
.filter-item label { font-size: 12px; font-weight: 500; color: var(--text-secondary); }
.filter-item select, .filter-item input { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); min-width: 130px; }
.checkbox-label { display: flex; flex-direction: row !important; align-items: center; gap: 8px; cursor: pointer; }
.filter-actions { display: flex; gap: 8px; }
@media (max-width: 768px) { .criteria-filter { flex-direction: column; align-items: stretch; } .filter-group { flex-direction: column; } .filter-item select, .filter-item input { width: 100%; } .filter-actions { justify-content: flex-end; } }
</style>