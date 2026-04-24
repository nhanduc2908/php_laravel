/**
 * Reports API
 * Quản lý báo cáo
 */

import apiClient from './client'

export const reportsApi = {
    // Tạo báo cáo
    generateReport(data) {
        return apiClient.post('/api/v1/reports/generate', data)
    },
    
    // Tải báo cáo
    downloadReport(id) {
        return apiClient.get(`/api/v1/reports/download/${id}`, {
            responseType: 'blob'
        })
    },
    
    // Lấy danh sách báo cáo
    getReports(params = {}) {
        return apiClient.get('/api/v1/reports', { params })
    },
    
    // Lên lịch báo cáo
    scheduleReport(data) {
        return apiClient.post('/api/v1/reports/schedule', data)
    },
    
    // Lấy danh sách template
    getTemplates() {
        return apiClient.get('/api/v1/reports/templates')
    },
    
    // Xóa báo cáo
    deleteReport(id) {
        return apiClient.delete(`/api/v1/reports/${id}`)
    }
}