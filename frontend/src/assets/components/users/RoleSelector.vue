<template>
    <div class="role-selector">
        <select class="form-control" :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" :class="{ 'is-invalid': error }">
            <option value="">-- Chọn vai trò --</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>
        <div class="invalid-feedback" v-if="error">{{ error }}</div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({ modelValue: { type: [Number, String], default: null }, error: { type: String, default: '' } })
const emit = defineEmits(['update:modelValue'])
const roles = ref([])

onMounted(async () => {
    const response = await fetch('/api/v1/roles')
    const data = await response.json()
    roles.value = data.data || []
})
</script>

<style scoped>
.role-selector { width: 100%; }
.is-invalid { border-color: var(--danger); }
.invalid-feedback { color: var(--danger); font-size: 12px; margin-top: 4px; }
</style>