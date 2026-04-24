<template>
    <div class="pagination-container" v-if="totalPages > 1">
        <button class="pagination-btn" :disabled="currentPage === 1" @click="goToPage(1)"><i class="fas fa-angle-double-left"></i></button>
        <button class="pagination-btn" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)"><i class="fas fa-angle-left"></i></button>
        <button v-for="page in visiblePages" :key="page" class="pagination-btn" :class="{ active: page === currentPage }" @click="goToPage(page)">{{ page }}</button>
        <button class="pagination-btn" :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)"><i class="fas fa-angle-right"></i></button>
        <button class="pagination-btn" :disabled="currentPage === totalPages" @click="goToPage(totalPages)"><i class="fas fa-angle-double-right"></i></button>
        <span class="pagination-info">Trang {{ currentPage }} / {{ totalPages }} (Tổng {{ totalItems }} bản ghi)</span>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ currentPage: { type: Number, required: true }, totalPages: { type: Number, required: true }, totalItems: { type: Number, default: 0 }, maxVisible: { type: Number, default: 5 } })
const emit = defineEmits(['page-change'])

const visiblePages = computed(() => { const pages = []; let start = Math.max(1, props.currentPage - Math.floor(props.maxVisible / 2)); let end = Math.min(props.totalPages, start + props.maxVisible - 1); if (end - start + 1 < props.maxVisible) start = Math.max(1, end - props.maxVisible + 1); for (let i = start; i <= end; i++) pages.push(i); return pages })
const goToPage = (page) => { if (page !== props.currentPage && page >= 1 && page <= props.totalPages) emit('page-change', page) }
</script>

<style scoped>
.pagination-container { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 20px; flex-wrap: wrap; }
.pagination-btn { min-width: 36px; height: 36px; padding: 0 8px; border: 1px solid var(--border-color); background: var(--bg-primary); border-radius: 6px; cursor: pointer; transition: all 0.2s; }
.pagination-btn:hover:not(:disabled) { background: var(--primary-600); color: white; border-color: var(--primary-600); }
.pagination-btn.active { background: var(--primary-600); color: white; border-color: var(--primary-600); }
.pagination-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.pagination-info { margin-left: 12px; font-size: 0.875rem; color: var(--text-secondary); }
@media (max-width: 640px) { .pagination-info { width: 100%; text-align: center; margin-top: 8px; } }
</style>