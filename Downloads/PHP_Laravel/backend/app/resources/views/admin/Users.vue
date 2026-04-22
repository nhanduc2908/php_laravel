<template>
    <div class="users-management">
        <div class="header-actions"><h2>Quản lý người dùng</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Thêm người dùng</button></div>
        <div class="filters"><input type="text" v-model="search" placeholder="Tìm kiếm..." @input="fetchUsers"><select v-model="roleFilter" @change="fetchUsers"><option value="">Tất cả</option><option value="2">Admin</option><option value="3">Security Officer</option><option value="4">Viewer</option><option value="5">Auditor</option></select></div>
        <table class="data-table"><thead><tr><th>Tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="user in users" :key="user.id"><td>{{ user.name }}</td><td>{{ user.email }}</td><td>{{ user.role_name }}</td><td><span :class="user.is_active ? 'badge-success' : 'badge-danger'">{{ user.is_active ? 'Active' : 'Inactive' }}</span></td>
        <td class="actions"><button @click="editUser(user)"><i class="fas fa-edit"></i></button><button @click="deleteUser(user.id)"><i class="fas fa-trash"></i></button><button @click="resetPassword(user.id)"><i class="fas fa-key"></i></button></td></tr></tbody></table>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchUsers" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useUserStore } from '@/stores/user'
const userStore = useUserStore()
const users = ref([]); const search = ref(''); const roleFilter = ref(''); const currentPage = ref(1); const totalPages = ref(1)
const fetchUsers = async () => { const res = await userStore.fetch({ search: search.value, role: roleFilter.value, page: currentPage.value }); users.value = res.data; totalPages.value = res.last_page }
const editUser = (user) => { /* Edit logic */ }
const deleteUser = async (id) => { if (confirm('Xóa?')) await userStore.delete(id); fetchUsers() }
const resetPassword = async (id) => { await userStore.resetPassword(id); alert('Đã gửi email reset mật khẩu') }
onMounted(fetchUsers)
</script>