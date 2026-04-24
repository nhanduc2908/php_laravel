/**
 * Servers API
 * Quản lý máy chủ
 */

import apiClient from './client'

export const serversApi = {
    // Lấy danh sách servers
    getServers(params = {}) {
        return apiClient.get('/api/v1/servers', { params })
    },
    
    // Lấy chi tiết server
    getServer(id) {
        return apiClient.get(`/api/v1/servers/${id}`)
    },
    
    // Tạo server mới
    createServer(data) {
        return apiClient.post('/api/v1/servers', data)
    },
    
    // Cập nhật server
    updateServer(id, data) {
        return apiClient.put(`/api/v1/servers/${id}`, data)
    },
    
    // Xóa server
    deleteServer(id) {
        return apiClient.delete(`/api/v1/servers/${id}`)
    },
    
    // Test kết nối SSH
    testConnection(id) {
        return apiClient.post(`/api/v1/servers/${id}/test-connection`)
    },
    
    // Quét server
    scanServer(id) {
        return apiClient.post(`/api/v1/servers/${id}/scan`)
    },
    
    // Lấy metrics của server
    getServerMetrics(id) {
        return apiClient.get(`/api/v1/servers/${id}/metrics`)
    }
}