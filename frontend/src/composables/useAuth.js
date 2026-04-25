/**
 * useAuth - Composable for authentication logic
 */

import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

export function useAuth() {
    const router = useRouter()
    const authStore = useAuthStore()
    const toast = useToast()
    
    const isAuthenticated = computed(() => authStore.isAuthenticated)
    const isAdmin = computed(() => authStore.isAdmin)
    const isSuperAdmin = computed(() => authStore.isSuperAdmin)
    const currentUser = computed(() => authStore.user)
    const userName = computed(() => authStore.userName)
    const userAvatar = computed(() => authStore.userAvatar)
    const userRole = computed(() => authStore.userRole)
    
    const login = async (email, password) => {
        const result = await authStore.login(email, password)
        if (result.success && !result.twoFactorRequired) {
            toast.success('Đăng nhập thành công')
            const role = authStore.user?.role
            if (role === 'super_admin') router.push('/super-admin/dashboard')
            else if (role === 'admin') router.push('/admin/dashboard')
            else if (role === 'security_officer') router.push('/security/dashboard')
            else if (role === 'viewer') router.push('/viewer/dashboard')
            else if (role === 'auditor') router.push('/auditor/dashboard')
            else router.push('/dashboard')
        }
        return result
    }
    
    const register = async (data) => {
        const result = await authStore.register(data)
        if (result.success) {
            toast.success('Đăng ký thành công! Vui lòng đăng nhập')
            router.push('/login')
        }
        return result
    }
    
    const logout = async () => {
        await authStore.logout()
        toast.success('Đăng xuất thành công')
        router.push('/login')
    }
    
    const verify2FA = async (code) => {
        const result = await authStore.verify2FA(code)
        if (result.success) {
            toast.success('Xác thực thành công')
            router.push('/dashboard')
        }
        return result
    }
    
    const fetchUser = async () => {
        return await authStore.fetchCurrentUser()
    }
    
    const hasRole = (role) => {
        return currentUser.value?.role === role
    }
    
    const hasAnyRole = (roles) => {
        return roles.includes(currentUser.value?.role)
    }
    
    return {
        // State
        isAuthenticated,
        isAdmin,
        isSuperAdmin,
        currentUser,
        userName,
        userAvatar,
        userRole,
        // Methods
        login,
        register,
        logout,
        verify2FA,
        fetchUser,
        hasRole,
        hasAnyRole,
    }
}