<template>
    <div class="auth-container"><div class="auth-card"><div class="auth-header"><img src="/assets/images/logo.svg" alt="Logo" class="auth-logo"><h2>Quên mật khẩu</h2><p>Nhập email để nhận link đặt lại mật khẩu</p></div>
    <form @submit.prevent="handleForgot"><div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" v-model="email" placeholder="example@email.com" required></div>
    <button type="submit" class="btn btn-primary btn-block" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Gửi link đặt lại mật khẩu</button></form>
    <div class="auth-footer"><router-link to="/login">← Quay lại đăng nhập</router-link></div></div></div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

const toast = useToast()
const loading = ref(false)
const email = ref('')

const handleForgot = async () => {
    loading.value = true
    try { await fetch('/api/v1/auth/forgot-password', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email: email.value }) }); toast.success('Link đặt lại mật khẩu đã được gửi đến email của bạn') }
    catch (error) { toast.error('Gửi yêu cầu thất bại') }
    finally { loading.value = false }
}
</script>