<template>
    <div class="roles-management">
        <div class="header-actions">
            <h2>Quản lý vai trò & quyền</h2>
            <button class="btn-primary" @click="openCreateModal">
                <i class="fas fa-plus"></i> Thêm vai trò
            </button>
        </div>

        <div class="roles-permissions">
            <div class="roles-list">
                <h3>Danh sách vai trò</h3>
                <div v-for="role in roles" :key="role.id" 
                     :class="['role-item', { active: selectedRole?.id === role.id }]"
                     @click="selectRole(role)">
                    {{ role.name }}
                </div>
            </div>
            
            <div class="permissions-list" v-if="selectedRole">
                <h3>Quyền cho: {{ selectedRole.name }}</h3>
                <div class="permission-group" v-for="group in permissionGroups" :key="group.name">
                    <h4>{{ group.name }}</h4>
                    <label v-for="perm in group.permissions" :key="perm.id" class="permission-item">
                        <input type="checkbox" :value="perm.id" v-model="selectedPermissions" @change="updatePermissions">
                        {{ perm.name }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoleStore } from '@/stores/role'

const roleStore = useRoleStore()
const roles = ref([])
const selectedRole = ref(null)
const selectedPermissions = ref([])
const permissionGroups = ref([])

const selectRole = async (role) => {
    selectedRole.value = role
    const perms = await roleStore.getRolePermissions(role.id)
    selectedPermissions.value = perms.map(p => p.id)
}

const updatePermissions = async () => {
    await roleStore.assignPermissions(selectedRole.value.id, selectedPermissions.value)
}
onMounted(async () => { roles.value = await roleStore.fetchRoles() })
</script>