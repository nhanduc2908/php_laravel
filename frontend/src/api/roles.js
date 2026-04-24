/**
 * Roles API
 * Quản lý vai trò và quyền
 */

import apiClient from './client'

export const rolesApi = {
    // Lấy danh sách roles
    getRoles() {
        return apiClient.get('/api/v1/roles')
    },
    
    // Lấy chi tiết role
    getRole(id) {
        return apiClient.get(`/api/v1/roles/${id}`)
    },
    
    // Tạo role mới
    createRole(data) {
        return apiClient.post('/api/v1/roles', data)
    },
    
    // Cập nhật role
    updateRole(id, data) {
        return apiClient.put(`/api/v1/roles/${id}`, data)
    },
    
    // Xóa role
    deleteRole(id) {
        return apiClient.delete(`/api/v1/roles/${id}`)
    },
    
    // Lấy permissions của role
    getRolePermissions(id) {
        return apiClient.get(`/api/v1/roles/${id}/permissions`)
    },
    
    // Gán permission cho role
    assignPermission(roleId, permissionId) {
        return apiClient.post(`/api/v1/roles/${roleId}/permissions`, { permission_id: permissionId })
    },
    
    // Thu hồi permission
    revokePermission(roleId, permissionId) {
        return apiClient.delete(`/api/v1/roles/${roleId}/permissions/${permissionId}`)
    }
}