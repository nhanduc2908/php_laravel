<template>
    <div class="roles-management"><h2>Quản lý vai trò</h2><div class="roles-list"><div v-for="role in roles" :key="role.id" class="role-item" @click="selectRole(role)">{{ role.name }}</div></div>
    <div v-if="selectedRole"><h3>Quyền cho {{ selectedRole.name }}</h3><div class="permissions-grid"><label v-for="perm in permissions" :key="perm.id"><input type="checkbox" :value="perm.id" v-model="selectedPermissions" @change="updatePermissions"> {{ perm.name }}</label></div></div>
</div></template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoleStore } from '@/stores/role'
const roleStore = useRoleStore(); const roles = ref([]); const selectedRole = ref(null); const permissions = ref([]); const selectedPermissions = ref([])
const selectRole = async (role) => { selectedRole.value = role; const perms = await roleStore.getRolePermissions(role.id); selectedPermissions.value = perms.map(p => p.id) }
const updatePermissions = async () => { await roleStore.assignPermissions(selectedRole.value.id, selectedPermissions.value) }
onMounted(async () => { roles.value = await roleStore.fetchRoles(); permissions.value = await roleStore.fetchPermissions() })
</script>