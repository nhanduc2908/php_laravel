/**
 * Assessments API
 * Đánh giá an ninh
 */

import apiClient from './client'

export const assessmentsApi = {
    // Chạy đánh giá
    runAssessment(data) {
        return apiClient.post('/api/v1/assessments/run', data)
    },
    
    // Lấy kết quả đánh giá
    getAssessmentResult(id) {
        return apiClient.get(`/api/v1/assessments/${id}/result`)
    },
    
    // Lấy lịch sử đánh giá
    getAssessmentHistory(params = {}) {
        return apiClient.get('/api/v1/assessments/history', { params })
    },
    
    // Lấy trạng thái tuân thủ
    getComplianceStatus(serverId) {
        return apiClient.get(`/api/v1/assessments/compliance/${serverId}`)
    },
    
    // Lấy điểm server
    getServerScore(serverId) {
        return apiClient.get(`/api/v1/assessments/score/${serverId}`)
    },
    
    // So sánh kết quả
    compareAssessments(assessmentIds) {
        return apiClient.post('/api/v1/assessments/compare', { assessment_ids: assessmentIds })
    },
    
    // Xuất kết quả
    exportAssessment(id, format = 'pdf') {
        return apiClient.get(`/api/v1/assessments/${id}/export`, {
            params: { format },
            responseType: 'blob'
        })
    }
}