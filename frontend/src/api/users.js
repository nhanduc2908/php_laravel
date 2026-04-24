/**
 * Users API
 * Quản lý người dùng
 */

import apiClient from './client'

export const usersApi = {
    // Lấy danh sách users (có phân trang)
    getUsers(params = {}) {
        return apiClient.get('/api/v1/users', { params })
    },
    
    // Lấy chi tiết user
    getUser(id) {
        return apiClient.get(`/api/v1/users/${id}`)
    },
    
    // Tạo user mới
    createUser(data) {
        return apiClient.post('/api/v1/users', data)
    },
    
    // Cập nhật user
    updateUser(id, data) {
        return apiClient.put(`/api/v1/users/${id}`, data)
    },
    
    // Xóa user
    deleteUser(id) {
        return apiClient.delete(`/api/v1/users/${id}`)
    },
    
    // Gán role cho user
    assignRole(userId, roleId) {
        return apiClient.post(`/api/v1/users/${userId}/assign-role/${roleId}`)
    },
    
    // Lấy permissions của user
    getUserPermissions(id) {
        return apiClient.get(`/api/v1/users/${id}/permissions`)
    },
    
    // Reset mật khẩu user
    resetPassword(id) {
        return apiClient.post(`/api/v1/users/${id}/reset-password`)
    }
}