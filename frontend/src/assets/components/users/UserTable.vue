<template>
    <div class="user-table-wrapper">
        <DataTable
            :columns="columns"
            :data="users"
            :loading="loading"
            :striped="true"
            :hover="true"
            :actions="true"
            :pagination="true"
            :current-page="currentPage"
            :total-pages="totalPages"
            :total-items="totalItems"
            @sort="handleSort"
            @page-change="handlePageChange"
        >
            <template #actions="{ row }">
                <button class="action-btn" @click="editUser(row)" title="Sửa">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn" @click="resetPassword(row)" title="Reset mật khẩu">
                    <i class="fas fa-key"></i>
                </button>
                <button class="action-btn danger" @click="deleteUser(row)" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </template>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import DataTable from '@/components/common/DataTable.vue'

const props = defineProps({
    users: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    currentPage: { type: Number, default: 1 },
    totalPages: { type: Number, default: 1 },
    totalItems: { type: Number, default: 0 }
})

const emit = defineEmits(['edit', 'delete', 'reset-password', 'sort', 'page-change'])

const columns = [
    { key: 'id', label: 'ID', width: '60px', sortable: true },
    { key: 'avatar', label: 'Avatar', width: '60px', formatter: (v) => `<img src="${v || '/assets/images/default-avatar.png'}" class="avatar-img">` },
    { key: 'name', label: 'Họ tên', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'role_name', label: 'Vai trò', formatter: (v) => `<span class="role-badge">${v}</span>` },
    { key: 'is_active', label: 'Trạng thái', formatter: (v) => `<span class="status-badge ${v ? 'active' : 'inactive'}">${v ? 'Hoạt động' : 'Khóa'}</span>` },
    { key: 'last_login_at', label: 'Đăng nhập cuối', type: 'datetime', sortable: true }
]

const editUser = (user) => emit('edit', user)
const deleteUser = (user) => emit('delete', user)
const resetPassword = (user) => emit('reset-password', user)
const handleSort = (sort) => emit('sort', sort)
const handlePageChange = (page) => emit('page-change', page)
</script>

<style scoped>
.user-table-wrapper { width: 100%; overflow-x: auto; }
.avatar-img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
.role-badge { display: inline-block; padding: 4px 8px; background: var(--bg-secondary); border-radius: 20px; font-size: 12px; }
.status-badge { display: inline-block; padding: 4px 8px; border-radius: 20px; font-size: 12px; }
.status-badge.active { background: #d1fae5; color: #10b981; }
.status-badge.inactive { background: #fee2e2; color: #ef4444; }
.action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: var(--text-secondary); transition: all 0.2s; }
.action-btn:hover { background: var(--bg-secondary); color: var(--primary-600); }
.action-btn.danger:hover { color: var(--danger); }
</style>