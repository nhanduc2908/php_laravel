/**
 * Authentication API
 * Đăng nhập, đăng ký, đăng xuất, refresh token
 */

import apiClient from './client'

export const authApi = {
    // Đăng nhập
    login(email, password) {
        return apiClient.post('/api/v1/auth/login', { email, password })
    },
    
    // Đăng ký
    register(name, email, password, passwordConfirmation) {
        return apiClient.post('/api/v1/auth/register', {
            name,
            email,
            password,
            password_confirmation: passwordConfirmation
        })
    },
    
    // Đăng xuất
    logout() {
        return apiClient.post('/api/v1/auth/logout')
    },
    
    // Refresh token
    refreshToken() {
        return apiClient.post('/api/v1/auth/refresh')
    },
    
    // Lấy thông tin user hiện tại
    getMe() {
        return apiClient.get('/api/v1/auth/me')
    },
    
    // Quên mật khẩu
    forgotPassword(email) {
        return apiClient.post('/api/v1/auth/forgot-password', { email })
    },
    
    // Đặt lại mật khẩu
    resetPassword(email, token, password, passwordConfirmation) {
        return apiClient.post('/api/v1/auth/reset-password', {
            email,
            token,
            password,
            password_confirmation: passwordConfirmation
        })
    },
    
    // Xác thực 2FA
    verify2FA(code) {
        return apiClient.post('/api/v1/auth/2fa/verify', { code })
    }
}