<template>
    <div class="log-filter">
        <div class="filter-row">
            <div class="filter-group"><label>Từ ngày</label><input type="date" v-model="filters.date_from" @change="applyFilter"></div>
            <div class="filter-group"><label>Đến ngày</label><input type="date" v-model="filters.date_to" @change="applyFilter"></div>
            <div class="filter-group"><label>Người dùng</label>
                <select v-model="filters.user_id" @change="applyFilter"><option value="">Tất cả</option><option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option></select>
            </div>
            <div class="filter-group"><label>Hành động</label>
                <select v-model="filters.action" @change="applyFilter"><option value="">Tất cả</option><option value="login">Đăng nhập</option><option value="logout">Đăng xuất</option><option value="create">Tạo</option><option value="update">Cập nhật</option><option value="delete">Xóa</option><option value="view">Xem</option><option value="export">Xuất</option></select>
            </div>
            <div class="filter-group"><label>Tài nguyên</label>
                <select v-model="filters.resource" @change="applyFilter"><option value="">Tất cả</option><option value="user">Người dùng</option><option value="server">Máy chủ</option><option value="criteria">Tiêu chí</option><option value="assessment">Đánh giá</option><option value="file">Tệp</option></select>
            </div>
        </div>
        <div class="filter-actions"><button class="btn btn-secondary btn-sm" @click="resetFilters">Đặt lại</button><button class="btn btn-primary btn-sm" @click="exportLogs"><i class="fas fa-download"></i> Xuất logs</button></div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

const emit = defineEmits(['filter-change', 'export'])

const users = ref([])
const filters = reactive({ date_from: '', date_to: '', user_id: '', action: '', resource: '' })

const fetchUsers = async () => { const res = await fetch('/api/v1/users'); users.value = (await res.json()).data?.data || [] }
const applyFilter = () => emit('filter-change', { ...filters })
const resetFilters = () => { Object.keys(filters).forEach(k => filters[k] = ''); applyFilter() }
const exportLogs = () => emit('export', { ...filters })

onMounted(fetchUsers)
</script>

<style scoped>
.log-filter { background: var(--card-bg); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
.filter-row { display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end; margin-bottom: 16px; }
.filter-group { display: flex; flex-direction: column; gap: 6px; }
.filter-group label { font-size: 12px; font-weight: 500; color: var(--text-secondary); }
.filter-group input, .filter-group select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); min-width: 150px; }
.filter-actions { display: flex; justify-content: flex-end; gap: 12px; }
@media (max-width: 768px) { .filter-row { flex-direction: column; align-items: stretch; } .filter-group input, .filter-group select { width: 100%; } }
</style>