<template>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header"><img src="/assets/images/logo.svg" alt="Logo" class="auth-logo"><h2>Đăng ký</h2><p>Tạo tài khoản mới</p></div>
            <form @submit.prevent="handleRegister">
                <div class="form-group"><label class="form-label">Họ và tên</label><input type="text" class="form-control" v-model="form.name" placeholder="Nguyễn Văn A" required></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" v-model="form.email" placeholder="example@email.com" required></div>
                <div class="form-group"><label class="form-label">Mật khẩu</label><input type="password" class="form-control" v-model="form.password" placeholder="••••••••" required></div>
                <div class="form-group"><label class="form-label">Xác nhận mật khẩu</label><input type="password" class="form-control" v-model="form.password_confirmation" placeholder="••••••••" required></div>
                <button type="submit" class="btn btn-primary btn-block" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Đăng ký</button>
            </form>
            <div class="auth-footer">Đã có tài khoản? <router-link to="/login">Đăng nhập ngay</router-link></div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()
const loading = ref(false)
const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })

const handleRegister = async () => {
    if (form.password !== form.password_confirmation) { toast.error('Mật khẩu xác nhận không khớp'); return }
    loading.value = true
    try { await authStore.register(form); toast.success('Đăng ký thành công'); router.push('/login') }
    catch (error) { toast.error(error.message || 'Đăng ký thất bại') }
    finally { loading.value = false }
}
</script>

<style scoped>
.auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.auth-card { background: white; border-radius: 16px; padding: 40px; width: 100%; max-width: 450px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.auth-header { text-align: center; margin-bottom: 30px; }
.auth-logo { width: 60px; height: 60px; margin-bottom: 16px; }
.btn-block { width: 100%; }
.auth-footer { text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #eee; }
.auth-footer a { color: #667eea; text-decoration: none; }
</style>