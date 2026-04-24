/**
 * Testing API
 * Chạy và quản lý test
 */

import apiClient from './client'

export const testingApi = {
    // Chạy test
    runTests(params = {}) {
        return apiClient.post('/api/v1/testing/run', params)
    },
    
    // Lấy kết quả test
    getResults() {
        return apiClient.get('/api/v1/testing/results')
    },
    
    // Lấy code coverage
    getCoverage() {
        return apiClient.get('/api/v1/testing/coverage')
    },
    
    // Lấy lịch sử test
    getHistory() {
        return apiClient.get('/api/v1/testing/history')
    }
}