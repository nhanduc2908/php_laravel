<template>
    <div class="categories-management"><div class="header-actions"><h2>Quản lý danh mục (17 danh mục)</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Thêm danh mục</button></div>
    <div class="categories-tree"><div v-for="category in categories" :key="category.id" class="category-item"><div class="category-header" :style="{ borderLeftColor: category.color }"><i :class="category.icon" :style="{ color: category.color }"></i><span class="category-name">{{ category.name }}</span><span class="category-code">{{ category.code }}</span><span class="category-weight">Weight: {{ category.weight }}</span><div class="category-actions"><button @click="editCategory(category)"><i class="fas fa-edit"></i></button><button @click="deleteCategory(category.id)"><i class="fas fa-trash"></i></button></div></div>
    <div class="category-criteria" v-if="category.criteria"><div v-for="criterion in category.criteria" :key="criterion.id" class="criterion-item"><span class="criterion-code">{{ criterion.code }}</span><span class="criterion-name">{{ criterion.name }}</span><span class="criterion-weight">({{ criterion.weight }})</span></div></div></div></div></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCategoryStore } from '@/stores/category'
const categoryStore = useCategoryStore(); const categories = ref([])
const fetchCategories = async () => { categories.value = await categoryStore.fetchAll() }
onMounted(fetchCategories)
</script>