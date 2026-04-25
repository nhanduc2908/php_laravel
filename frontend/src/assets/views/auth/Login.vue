<template>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="/assets/images/logo.svg" alt="Logo" class="auth-logo">
                <h2>Đăng nhập</h2>
                <p>Chào mừng bạn quay trở lại</p>
            </div>
            <form @submit.prevent="handleLogin">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" v-model="form.email" placeholder="example@email.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" v-model="form.password" placeholder="••••••••" required>
                </div>
                <div class="form-options">
                    <label class="checkbox"><input type="checkbox" v-model="form.remember"> Ghi nhớ đăng nhập</label>
                    <router-link to="/forgot-password" class="forgot-link">Quên mật khẩu?</router-link>
                </div>
                <button type="submit" class="btn btn-primary btn-block" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Đăng nhập</button>
            </form>
            <div class="auth-footer">Chưa có tài khoản? <router-link to="/register">Đăng ký ngay</router-link></div>
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

const form = reactive({ email: '', password: '', remember: false })

const handleLogin = async () => {
    loading.value = true
    try {
        await authStore.login(form.email, form.password)
        toast.success('Đăng nhập thành công')
        router.push('/dashboard')
    } catch (error) {
        toast.error(error.message || 'Đăng nhập thất bại')
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.auth-card { background: white; border-radius: 16px; padding: 40px; width: 100%; max-width: 450px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.auth-header { text-align: center; margin-bottom: 30px; }
.auth-logo { width: 60px; height: 60px; margin-bottom: 16px; }
.auth-header h2 { margin-bottom: 8px; color: #333; }
.auth-header p { color: #666; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
.form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: all 0.2s; }
.form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); }
.form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 14px; }
.checkbox { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.forgot-link { color: #667eea; text-decoration: none; }
.btn-block { width: 100%; }
.auth-footer { text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #eee; color: #666; }
.auth-footer a { color: #667eea; text-decoration: none; }
</style>