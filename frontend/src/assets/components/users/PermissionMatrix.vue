PermissionMatrix.vue<template>
    <div class="permission-matrix">
        <div class="matrix-header">
            <div class="resource-col">Tài nguyên</div>
            <div class="actions-col">Quyền</div>
        </div>
        <div class="matrix-body">
            <div v-for="resource in resources" :key="resource.key" class="matrix-row">
                <div class="resource-col">{{ resource.label }}</div>
                <div class="actions-col">
                    <label v-for="action in actions" :key="action.key" class="permission-checkbox">
                        <input type="checkbox" :value="`${resource.key}.${action.key}`" v-model="selectedPermissions" @change="updatePermissions">
                        {{ action.label }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({ roleId: { type: Number, required: true }, value: { type: Array, default: () => [] } })
const emit = defineEmits(['update:modelValue', 'change'])

const resources = ref([])
const actions = ref([])
const selectedPermissions = ref([])

const fetchPermissions = async () => {
    const response = await fetch('/api/v1/permissions')
    const data = await response.json()
    const perms = data.data || []
    resources.value = [...new Map(perms.map(p => [p.resource, { key: p.resource, label: p.resource_label }])).values()]
    actions.value = [...new Map(perms.map(p => [p.action, { key: p.action, label: p.action_label }])).values()]
}

const fetchRolePermissions = async () => {
    const response = await fetch(`/api/v1/roles/${props.roleId}/permissions`)
    const data = await response.json()
    selectedPermissions.value = (data.data || []).map(p => `${p.resource}.${p.action}`)
}

const updatePermissions = () => { emit('update:modelValue', selectedPermissions.value); emit('change', selectedPermissions.value) }

watch(() => props.roleId, () => { if (props.roleId) fetchRolePermissions() })
onMounted(() => { fetchPermissions(); if (props.roleId) fetchRolePermissions() })
</script>

<style scoped>
.permission-matrix { border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; }
.matrix-header, .matrix-row { display: flex; border-bottom: 1px solid var(--border-color); }
.matrix-header { background: var(--bg-secondary); font-weight: 600; }
.resource-col { width: 200px; padding: 12px 16px; border-right: 1px solid var(--border-color); }
.actions-col { flex: 1; padding: 12px 16px; display: flex; gap: 16px; flex-wrap: wrap; }
.permission-checkbox { display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 14px; }
.permission-checkbox input { cursor: pointer; }
@media (max-width: 768px) { .matrix-header, .matrix-row { flex-direction: column; } .resource-col { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); } .actions-col { padding-left: 32px; } }
</style>