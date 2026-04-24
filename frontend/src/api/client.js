/**
 * Axios Client - HTTP Request Handler
 * Cấu hình axios instance cho toàn bộ ứng dụng
 */

import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

// Create axios instance
const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000',
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

// Request interceptor - Add token
apiClient.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore()
        if (authStore.token) {
            config.headers.Authorization = `Bearer ${authStore.token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Response interceptor - Handle errors
apiClient.interceptors.response.use(
    (response) => {
        return response.data
    },
    async (error) => {
        const toast = useToast()
        const originalRequest = error.config
        
        // Handle 401 Unauthorized
        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true
            
            const authStore = useAuthStore()
            try {
                await authStore.refreshToken()
                return apiClient(originalRequest)
            } catch (refreshError) {
                authStore.logout()
                window.location.href = '/login'
                return Promise.reject(refreshError)
            }
        }
        
        // Handle 403 Forbidden
        if (error.response?.status === 403) {
            toast.error('Bạn không có quyền truy cập')
        }
        
        // Handle 404 Not Found
        if (error.response?.status === 404) {
            toast.error('Không tìm thấy tài nguyên')
        }
        
        // Handle 422 Validation
        if (error.response?.status === 422) {
            const errors = error.response.data?.errors
            if (errors) {
                Object.values(errors).forEach(err => {
                    toast.error(err[0])
                })
            }
        }
        
        // Handle 500 Server Error
        if (error.response?.status >= 500) {
            toast.error('Lỗi máy chủ, vui lòng thử lại sau')
        }
        
        return Promise.reject(error)
    }
)

export default apiClient