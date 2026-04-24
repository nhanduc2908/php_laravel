<template>
    <div class="search-filter">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" v-model="searchText" :placeholder="placeholder" @input="onSearch" @keyup.enter="onSearch">
            <button v-if="searchText" class="clear-btn" @click="clearSearch"><i class="fas fa-times"></i></button>
        </div>
        <div class="filter-group" v-if="filters.length">
            <select v-for="filter in filters" :key="filter.key" v-model="filterValues[filter.key]" @change="onFilterChange">
                <option value="">Tất cả {{ filter.label }}</option>
                <option v-for="opt in filter.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
            </select>
        </div>
        <button class="refresh-btn" @click="refresh" title="Làm mới"><i class="fas fa-sync-alt"></i></button>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

const props = defineProps({ modelValue: { type: String, default: '' }, placeholder: { type: String, default: 'Tìm kiếm...' }, filters: { type: Array, default: () => [] }, debounce: { type: Number, default: 300 } })
const emit = defineEmits(['update:modelValue', 'search', 'filter-change', 'refresh'])
const searchText = ref(props.modelValue)
const filterValues = reactive({})

let debounceTimer = null

props.filters.forEach(f => { if (!filterValues[f.key]) filterValues[f.key] = '' })
watch(searchText, (val) => { emit('update:modelValue', val); if (debounceTimer) clearTimeout(debounceTimer); debounceTimer = setTimeout(() => emit('search', { search: val, filters: filterValues }), props.debounce) })
const onSearch = () => emit('search', { search: searchText.value, filters: filterValues })
const onFilterChange = () => emit('filter-change', filterValues)
const clearSearch = () => { searchText.value = ''; onSearch() }
const refresh = () => emit('refresh')
</script>

<style scoped>
.search-filter { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
.search-input { flex: 1; min-width: 200px; position: relative; }
.search-input i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); }
.search-input input { width: 100%; padding: 8px 12px 8px 36px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); color: var(--text-primary); }
.search-input .clear-btn { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-secondary); }
.filter-group { display: flex; gap: 8px; flex-wrap: wrap; }
.filter-group select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); color: var(--text-primary); cursor: pointer; }
.refresh-btn { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; padding: 8px 12px; cursor: pointer; transition: all 0.2s; }
.refresh-btn:hover { background: var(--primary-600); color: white; }
</style>