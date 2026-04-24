/**
 * Profile API
 * Quản lý hồ sơ cá nhân
 */

import apiClient from './client'

export const profileApi = {
    // Lấy thông tin profile
    getProfile() {
        return apiClient.get('/api/v1/profile')
    },
    
    // Cập nhật profile
    updateProfile(data) {
        return apiClient.put('/api/v1/profile', data)
    },
    
    // Upload avatar
    uploadAvatar(avatar) {
        const formData = new FormData()
        formData.append('avatar', avatar)
        return apiClient.post('/api/v1/profile/avatar', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
    },
    
    // Đổi mật khẩu
    changePassword(currentPassword, newPassword) {
        return apiClient.post('/api/v1/profile/change-password', {
            current_password: currentPassword,
            new_password: newPassword
        })
    },
    
    // Bật 2FA
    enable2FA() {
        return apiClient.post('/api/v1/profile/2fa/enable')
    },
    
    // Tắt 2FA
    disable2FA() {
        return apiClient.post('/api/v1/profile/2fa/disable')
    },
    
    // Lấy danh sách session
    getSessions() {
        return apiClient.get('/api/v1/profile/sessions')
    }
}