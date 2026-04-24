/**
 * Audit API
 * Xem nhật ký kiểm toán
 */

import apiClient from './client'

export const auditApi = {
    // Lấy danh sách audit logs
    getLogs(params = {}) {
        return apiClient.get('/api/v1/audit/logs', { params })
    },
    
    // Lấy chi tiết log
    getLog(id) {
        return apiClient.get(`/api/v1/audit/logs/${id}`)
    },
    
    // Lấy log theo user
    getUserLogs(userId) {
        return apiClient.get(`/api/v1/audit/user/${userId}`)
    },
    
    // Xuất logs
    exportLogs(params = {}) {
        return apiClient.get('/api/v1/audit/export', {
            params,
            responseType: 'blob'
        })
    }
}