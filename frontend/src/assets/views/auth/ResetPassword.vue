<template>
    <div class="auth-container"><div class="auth-card"><div class="auth-header"><img src="/assets/images/logo.svg" alt="Logo" class="auth-logo"><h2>Đặt lại mật khẩu</h2><p>Vui lòng nhập mật khẩu mới</p></div>
    <form @submit.prevent="handleReset"><div class="form-group"><label class="form-label">Mật khẩu mới</label><input type="password" class="form-control" v-model="form.password" required></div>
    <div class="form-group"><label class="form-label">Xác nhận mật khẩu</label><input type="password" class="form-control" v-model="form.password_confirmation" required></div>
    <button type="submit" class="btn btn-primary btn-block" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Đặt lại mật khẩu</button></form>
    <div class="auth-footer"><router-link to="/login">← Quay lại đăng nhập</router-link></div></div></div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'

const router = useRouter(); const route = useRoute(); const toast = useToast()
const loading = ref(false); const token = ref(''); const email = ref('')
const form = reactive({ password: '', password_confirmation: '' })

onMounted(() => { token.value = route.query.token; email.value = route.query.email })

const handleReset = async () => {
    if (form.password !== form.password_confirmation) { toast.error('Mật khẩu xác nhận không khớp'); return }
    loading.value = true
    try { await fetch('/api/v1/auth/reset-password', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email: email.value, token: token.value, password: form.password, password_confirmation: form.password_confirmation }) }); toast.success('Đặt lại mật khẩu thành công'); router.push('/login') }
    catch (error) { toast.error('Đặt lại mật khẩu thất bại') }
    finally { loading.value = false }
}
</script>