<template>
    <div class="profile-form">
        <form @submit.prevent="saveProfile">
            <div class="form-row"><div class="form-group"><label class="form-label">Họ và tên</label><input type="text" class="form-control" v-model="form.name" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" v-model="form.email" required></div></div>
            <div class="form-group"><label class="form-label">Số điện thoại</label><input type="tel" class="form-control" v-model="form.phone"></div>
            <div class="form-group"><label class="form-label">Phòng ban</label><input type="text" class="form-control" v-model="form.department"></div>
            <div class="form-group"><label class="form-label">Chức vụ</label><input type="text" class="form-control" v-model="form.position"></div>
            <div class="form-group"><label class="form-label">Bio</label><textarea class="form-control" v-model="form.bio" rows="3"></textarea></div>
            <div class="form-actions"><button type="submit" class="btn btn-primary" :disabled="saving"><i v-if="saving" class="fas fa-spinner fa-spin"></i> Cập nhật hồ sơ</button></div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()
const saving = ref(false)

const form = reactive({ name: '', email: '', phone: '', department: '', position: '', bio: '' })

const loadProfile = async () => { const res = await fetch('/api/v1/profile'); const data = await res.json(); Object.assign(form, data.data || {}) }
const saveProfile = async () => { saving.value = true; await fetch('/api/v1/profile', { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(form) }); authStore.fetchCurrentUser(); toast.success('Cập nhật hồ sơ thành công'); saving.value = false }
onMounted(loadProfile)
</script>

<style scoped>
.profile-form { max-width: 600px; margin: 0 auto; }
.form-row { display: flex; gap: 20px; margin-bottom: 20px; }
.form-row .form-group { flex: 1; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.form-control { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
.form-actions { display: flex; justify-content: flex-end; margin-top: 24px; }
@media (max-width: 640px) { .form-row { flex-direction: column; gap: 16px; } }
</style>