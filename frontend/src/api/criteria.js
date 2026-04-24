/**
 * Criteria API
 * Quản lý 280 tiêu chí
 */

import apiClient from './client'

export const criteriaApi = {
    // Lấy danh sách criteria
    getCriteria(params = {}) {
        return apiClient.get('/api/v1/criteria', { params })
    },
    
    // Lấy chi tiết criteria
    getCriteriaItem(id) {
        return apiClient.get(`/api/v1/criteria/${id}`)
    },
    
    // Tạo criteria mới
    createCriteria(data) {
        return apiClient.post('/api/v1/criteria', data)
    },
    
    // Cập nhật criteria
    updateCriteria(id, data) {
        return apiClient.put(`/api/v1/criteria/${id}`, data)
    },
    
    // Xóa criteria
    deleteCriteria(id) {
        return apiClient.delete(`/api/v1/criteria/${id}`)
    },
    
    // Import criteria từ Excel
    importCriteria(file) {
        const formData = new FormData()
        formData.append('file', file)
        return apiClient.post('/api/v1/criteria/import', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
    },
    
    // Export criteria ra Excel
    exportCriteria() {
        return apiClient.get('/api/v1/criteria/export', { responseType: 'blob' })
    },
    
    // Tìm kiếm criteria
    searchCriteria(keyword) {
        return apiClient.get('/api/v1/criteria/search', { params: { q: keyword } })
    }
}