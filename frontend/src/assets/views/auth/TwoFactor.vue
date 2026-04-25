<template>
    <div class="auth-container"><div class="auth-card"><div class="auth-header"><img src="/assets/images/logo.svg" alt="Logo" class="auth-logo"><h2>Xác thực hai yếu tố</h2><p>Nhập mã xác thực từ ứng dụng Google Authenticator</p></div>
    <form @submit.prevent="handleVerify"><div class="form-group"><label class="form-label">Mã xác thực</label><input type="text" class="form-control" v-model="code" placeholder="123456" maxlength="6" required></div>
    <button type="submit" class="btn btn-primary btn-block" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Xác thực</button></form>
    <div class="auth-footer"><router-link to="/login">← Quay lại đăng nhập</router-link></div></div></div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const router = useRouter(); const authStore = useAuthStore(); const toast = useToast()
const loading = ref(false); const code = ref('')

const handleVerify = async () => {
    loading.value = true
    try { await authStore.verify2FA(code.value); toast.success('Xác thực thành công'); router.push('/dashboard') }
    catch (error) { toast.error('Mã xác thực không đúng') }
    finally { loading.value = false }
}
</script>