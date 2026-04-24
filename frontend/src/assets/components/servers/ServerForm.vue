<template>
    <div class="server-form">
        <form @submit.prevent="submit">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Tên máy chủ <span class="required">*</span></label>
                    <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" required>
                    <div class="invalid-feedback">{{ errors.name }}</div>
                </div>
                <div class="form-group"><label class="form-label">Địa chỉ IP/Hostname <span class="required">*</span></label>
                    <input type="text" class="form-control" v-model="form.host" :class="{ 'is-invalid': errors.host }" required>
                    <div class="invalid-feedback">{{ errors.host }}</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Cổng SSH</label><input type="number" class="form-control" v-model="form.port"></div>
                <div class="form-group"><label class="form-label">Tên đăng nhập <span class="required">*</span></label>
                    <input type="text" class="form-control" v-model="form.username" :class="{ 'is-invalid': errors.username }" required>
                    <div class="invalid-feedback">{{ errors.username }}</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Mật khẩu</label><input type="password" class="form-control" v-model="form.password"></div>
                <div class="form-group"><label class="form-label">SSH Key</label><textarea class="form-control" v-model="form.ssh_key" rows="3" placeholder="-----BEGIN RSA PRIVATE KEY-----"></textarea></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Hệ điều hành</label><input type="text" class="form-control" v-model="form.os_type"></div>
                <div class="form-group"><label class="form-label">Trạng thái</label>
                    <select class="form-control" v-model="form.status"><option value="active">Hoạt động</option><option value="inactive">Không hoạt động</option><option value="pending">Chờ xử lý</option></select>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Mô tả</label><textarea class="form-control" v-model="form.description" rows="3"></textarea></div>
            <div class="form-actions"><button type="button" class="btn btn-secondary" @click="cancel">Hủy</button><button type="submit" class="btn btn-primary" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i>{{ isEdit ? 'Cập nhật' : 'Thêm mới' }}</button></div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

const props = defineProps({ initialData: { type: Object, default: () => ({}) }, isEdit: { type: Boolean, default: false }, loading: { type: Boolean, default: false } })
const emit = defineEmits(['submit', 'cancel'])
const errors = ref({})
const form = reactive({ name: '', host: '', port: 22, username: '', password: '', ssh_key: '', os_type: '', status: 'active', description: '' })

watch(() => props.initialData, (val) => { if (val) Object.assign(form, val) }, { immediate: true, deep: true })

const validate = () => {
    const newErrors = {}
    if (!form.name) newErrors.name = 'Tên máy chủ là bắt buộc'
    if (!form.host) newErrors.host = 'Địa chỉ IP là bắt buộc'
    if (!form.username) newErrors.username = 'Tên đăng nhập là bắt buộc'
    errors.value = newErrors
    return Object.keys(newErrors).length === 0
}

const submit = () => { if (validate()) emit('submit', { ...form }) }
const cancel = () => emit('cancel')
</script>

<style scoped>
.server-form { max-width: 800px; margin: 0 auto; }
.form-row { display: flex; gap: 20px; margin-bottom: 20px; }
.form-row .form-group { flex: 1; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.required { color: var(--danger); }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
@media (max-width: 640px) { .form-row { flex-direction: column; gap: 16px; } }
</style>