/**
 * Vulnerabilities API
 * Quản lý lỗ hổng bảo mật
 */

import apiClient from './client'

export const vulnerabilitiesApi = {
    // Lấy danh sách lỗ hổng
    getVulnerabilities(params = {}) {
        return apiClient.get('/api/v1/vulnerabilities', { params })
    },
    
    // Lấy chi tiết lỗ hổng
    getVulnerability(id) {
        return apiClient.get(`/api/v1/vulnerabilities/${id}`)
    },
    
    // Tra cứu CVE
    lookupCVE(cveId) {
        return apiClient.get(`/api/v1/vulnerabilities/cve/${cveId}`)
    },
    
    // Đánh dấu đã sửa
    markFixed(id) {
        return apiClient.post(`/api/v1/vulnerabilities/${id}/mark-fixed`)
    },
    
    // Lấy thống kê theo mức độ
    getSeverityStats() {
        return apiClient.get('/api/v1/vulnerabilities/severity/stats')
    },
    
    // Lấy lỗ hổng theo server
    getByServer(serverId) {
        return apiClient.get(`/api/v1/vulnerabilities/server/${serverId}`)
    },
    
    // Lấy thống kê tổng hợp
    getStatistics() {
        return apiClient.get('/api/v1/vulnerabilities/statistics')
    }
}