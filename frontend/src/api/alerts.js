/**
 * Alerts API
 * Quản lý cảnh báo
 */

import apiClient from './client'

export const alertsApi = {
    // Lấy danh sách cảnh báo
    getAlerts(params = {}) {
        return apiClient.get('/api/v1/alerts', { params })
    },
    
    // Lấy chi tiết cảnh báo
    getAlert(id) {
        return apiClient.get(`/api/v1/alerts/${id}`)
    },
    
    // Đánh dấu đã đọc
    markRead(id) {
        return apiClient.post(`/api/v1/alerts/${id}/read`)
    },
    
    // Đánh dấu nhiều đã đọc
    bulkMarkRead(ids) {
        return apiClient.post('/api/v1/alerts/bulk-read', { ids })
    },
    
    // Giải quyết cảnh báo
    resolve(id) {
        return apiClient.post(`/api/v1/alerts/${id}/resolve`)
    },
    
    // Bỏ qua cảnh báo
    ignore(id) {
        return apiClient.post(`/api/v1/alerts/${id}/ignore`)
    },
    
    // Xóa cảnh báo cũ
    cleanOld() {
        return apiClient.delete('/api/v1/alerts/clean')
    }
}
