<template>
    <div class="file-list">
        <div class="list-header">
            <div class="view-toggle">
                <button class="toggle-btn" :class="{ active: viewMode === 'grid' }" @click="viewMode = 'grid'"><i class="fas fa-th"></i></button>
                <button class="toggle-btn" :class="{ active: viewMode === 'list' }" @click="viewMode = 'list'"><i class="fas fa-list"></i></button>
            </div>
            <div class="search-filter"><input type="text" v-model="searchQuery" placeholder="Tìm kiếm..."><select v-model="statusFilter"><option value="">Tất cả</option><option value="draft">Nháp</option><option value="published">Đã xuất bản</option><option value="archived">Lưu trữ</option></select></div>
        </div>
        <div v-if="loading" class="loading-state"><LoadingSpinner :is-loading="true" /></div>
        <div v-else-if="viewMode === 'grid'" class="files-grid"><FileCard v-for="file in filteredFiles" :key="file.id" :file="file" @click="viewFile(file)" @edit="editFile" @delete="deleteFile" @share="shareFile" /></div>
        <div v-else class="files-table"><DataTable :columns="columns" :data="filteredFiles" :actions="true" @row-click="viewFile"><template #actions="{ row }"><button class="action-btn" @click.stop="editFile(row)"><i class="fas fa-edit"></i></button><button class="action-btn" @click.stop="shareFile(row)"><i class="fas fa-share-alt"></i></button><button class="action-btn danger" @click.stop="deleteFile(row)"><i class="fas fa-trash"></i></button></template></DataTable></div>
        <Pagination v-if="pagination" :current-page="currentPage" :total-pages="totalPages" @page-change="loadFiles" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import FileCard from './FileCard.vue'
import DataTable from '@/components/common/DataTable.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import Pagination from '@/components/common/Pagination.vue'

const props = defineProps({ serverId: { type: Number, default: null } })
const emit = defineEmits(['view', 'edit', 'delete', 'share'])

const files = ref([])
const loading = ref(false)
const viewMode = ref('grid')
const searchQuery = ref('')
const statusFilter = ref('')
const currentPage = ref(1)
const totalPages = ref(1)
const pagination = ref(true)

const columns = [{ key: 'title', label: 'Tiêu đề' }, { key: 'server_name', label: 'Máy chủ' }, { key: 'version', label: 'Version' }, { key: 'status', label: 'Trạng thái' }, { key: 'updated_at', label: 'Cập nhật', type: 'datetime' }]
const filteredFiles = computed(() => files.value.filter(f => (!searchQuery.value || f.title.toLowerCase().includes(searchQuery.value.toLowerCase())) && (!statusFilter.value || f.status === statusFilter.value)))

const loadFiles = async () => { loading.value = true; const url = props.serverId ? `/api/v1/assessment-files?server_id=${props.serverId}&page=${currentPage.value}` : `/api/v1/assessment-files?page=${currentPage.value}`; const response = await fetch(url); const data = await response.json(); files.value = data.data?.data || []; totalPages.value = data.data?.last_page || 1; loading.value = false }
const viewFile = (file) => emit('view', file)
const editFile = (file) => emit('edit', file)
const deleteFile = (file) => emit('delete', file)
const shareFile = (file) => emit('share', file)

onMounted(loadFiles)
</script>

<style scoped>
.file-list { width: 100%; }
.list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
.view-toggle { display: flex; gap: 8px; background: var(--bg-secondary); border-radius: 8px; padding: 4px; }
.toggle-btn { padding: 6px 12px; background: none; border: none; border-radius: 6px; cursor: pointer; transition: all 0.2s; }
.toggle-btn.active { background: var(--primary-600); color: white; }
.search-filter { display: flex; gap: 12px; }
.search-filter input, .search-filter select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); min-width: 200px; }
.files-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
.action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.action-btn:hover { color: var(--primary-600); }
.action-btn.danger:hover { color: var(--danger); }
@media (max-width: 640px) { .list-header { flex-direction: column; align-items: stretch; } .search-filter { flex-direction: column; } .search-filter input, .search-filter select { width: 100%; } }
</style>