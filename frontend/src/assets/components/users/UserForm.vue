<template>
    <div class="user-form">
        <form @submit.prevent="submit">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Họ và tên <span class="required">*</span></label>
                    <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" required>
                    <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }" required>
                    <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Mật khẩu {{ isEdit ? '(để trống nếu không đổi)' : '*' }}</label>
                    <input type="password" class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }">
                    <div class="invalid-feedback" v-if="errors.password">{{ errors.password }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" v-model="form.password_confirmation">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Vai trò <span class="required">*</span></label>
                    <RoleSelector v-model="form.role_id" :error="errors.role_id" />
                </div>
                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" v-model="form.is_active"> Hoạt động</label>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" @click="cancel">Hủy</button>
                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <i v-if="loading" class="fas fa-spinner fa-spin"></i>
                    {{ isEdit ? 'Cập nhật' : 'Thêm mới' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import RoleSelector from './RoleSelector.vue'

const props = defineProps({ initialData: { type: Object, default: () => ({}) }, isEdit: { type: Boolean, default: false }, loading: { type: Boolean, default: false } })
const emit = defineEmits(['submit', 'cancel'])
const errors = ref({})

const form = reactive({
    name: '', email: '', password: '', password_confirmation: '', role_id: null, is_active: true
})

watch(() => props.initialData, (val) => { if (val) Object.assign(form, val) }, { immediate: true, deep: true })

const validate = () => {
    const newErrors = {}
    if (!form.name) newErrors.name = 'Họ tên là bắt buộc'
    if (!form.email) newErrors.email = 'Email là bắt buộc'
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) newErrors.email = 'Email không hợp lệ'
    if (!props.isEdit && !form.password) newErrors.password = 'Mật khẩu là bắt buộc'
    if (form.password && form.password !== form.password_confirmation) newErrors.password_confirmation = 'Mật khẩu xác nhận không khớp'
    if (!form.role_id) newErrors.role_id = 'Vai trò là bắt buộc'
    errors.value = newErrors
    return Object.keys(newErrors).length === 0
}

const submit = () => { if (validate()) emit('submit', { ...form }) }
const cancel = () => emit('cancel')
</script>

<style scoped>
.user-form { max-width: 800px; margin: 0 auto; }
.form-row { display: flex; gap: 20px; margin-bottom: 20px; }
.form-row .form-group { flex: 1; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.required { color: var(--danger); }
.checkbox-group { display: flex; align-items: center; gap: 16px; margin-top: 8px; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
.is-invalid { border-color: var(--danger); }
.invalid-feedback { color: var(--danger); font-size: 12px; margin-top: 4px; }
@media (max-width: 640px) { .form-row { flex-direction: column; gap: 16px; } }
</style>