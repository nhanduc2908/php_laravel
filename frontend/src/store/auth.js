/**
 * AUTH STORE - Xác thực người dùng
 * Quản lý đăng nhập, đăng xuất, token, thông tin user
 */

import { defineStore } from 'pinia'
import { authApi } from '@/api/auth'
import { useToast } from 'vue-toastification'
import router from '@/router'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('access_token') || null,
        refreshToken: localStorage.getItem('refresh_token') || null,
        isLoading: false,
        twoFactorRequired: false,
        twoFactorToken: null,
    }),
    
    getters: {
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.role === 'admin' || state.user?.role === 'super_admin',
        isSuperAdmin: (state) => state.user?.role === 'super_admin',
        userName: (state) => state.user?.name || 'Guest',
        userAvatar: (state) => state.user?.avatar || '/assets/images/default-avatar.png',
        userRole: (state) => state.user?.role || null,
        isTokenExpiringSoon: (state) => {
            if (!state.token) return false
            try {
                const payload = JSON.parse(atob(state.token.split('.')[1]))
                const exp = payload.exp * 1000
                const now = Date.now()
                return (exp - now) < 5 * 60 * 1000 // 5 minutes
            } catch {
                return false
            }
        },
    },
    
    actions: {
        setToken(token, refresh = null) {
            this.token = token
            if (refresh) this.refreshToken = refresh
            if (token) localStorage.setItem('access_token', token)
            if (refresh) localStorage.setItem('refresh_token', refresh)
        },
        
        clearToken() {
            this.token = null
            this.refreshToken = null
            localStorage.removeItem('access_token')
            localStorage.removeItem('refresh_token')
        },
        
        async login(email, password) {
            this.isLoading = true
            try {
                const response = await authApi.login(email, password)
                if (response.status === 'success') {
                    const { access_token, refresh_token, user, two_factor_required } = response.data
                    if (two_factor_required) {
                        this.twoFactorRequired = true
                        this.twoFactorToken = access_token
                        router.push('/2fa')
                        return { success: true, twoFactorRequired: true }
                    }
                    this.setToken(access_token, refresh_token)
                    this.user = user
                    return { success: true, user }
                }
                throw new Error(response.message)
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Đăng nhập thất bại')
                return { success: false, error: error.message }
            } finally {
                this.isLoading = false
            }
        },
        
        async register(data) {
            this.isLoading = true
            try {
                const response = await authApi.register(data.name, data.email, data.password, data.password_confirmation)
                if (response.status === 'success') {
                    return { success: true }
                }
                throw new Error(response.message)
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Đăng ký thất bại')
                return { success: false, error: error.message }
            } finally {
                this.isLoading = false
            }
        },
        
        async verify2FA(code) {
            this.isLoading = true
            try {
                const response = await authApi.verify2FA(code, this.twoFactorToken)
                if (response.status === 'success') {
                    const { access_token, refresh_token, user } = response.data
                    this.setToken(access_token, refresh_token)
                    this.user = user
                    this.twoFactorRequired = false
                    this.twoFactorToken = null
                    return { success: true }
                }
                throw new Error(response.message)
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Mã xác thực không đúng')
                return { success: false, error: error.message }
            } finally {
                this.isLoading = false
            }
        },
        
        async fetchCurrentUser() {
            if (!this.token) return null
            try {
                const response = await authApi.getMe()
                if (response.status === 'success') {
                    this.user = response.data
                    return this.user
                }
            } catch (error) {
                console.error('Fetch user failed:', error)
                this.clearToken()
            }
            return null
        },
        
        async refreshToken() {
            if (!this.refreshToken) return false
            try {
                const response = await authApi.refreshToken()
                if (response.status === 'success') {
                    const { access_token } = response.data
                    this.token = access_token
                    localStorage.setItem('access_token', access_token)
                    return true
                }
            } catch (error) {
                this.clearToken()
            }
            return false
        },
        
        async logout() {
            try {
                if (this.token) {
                    await authApi.logout()
                }
            } catch (error) {
                console.error('Logout error:', error)
            } finally {
                this.clearToken()
                this.user = null
                this.twoFactorRequired = false
                router.push('/login')
                const toast = useToast()
                toast.success('Đăng xuất thành công')
            }
        },
        
        updateUser(data) {
            this.user = { ...this.user, ...data }
        },
    },
    
    persist: {
        key: 'auth',
        storage: localStorage,
        paths: ['token', 'refreshToken', 'user'],
    },
})