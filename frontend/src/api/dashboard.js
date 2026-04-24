/**
 * Dashboard API
 * Thống kê và biểu đồ
 */

import apiClient from './client'

export const dashboardApi = {
    // Lấy thống kê tổng quan
    getStats() {
        return apiClient.get('/api/v1/dashboard/stats')
    },
    
    // Lấy dữ liệu biểu đồ
    getCharts() {
        return apiClient.get('/api/v1/dashboard/charts')
    },
    
    // Lấy hoạt động gần đây
    getRecentActivities() {
        return apiClient.get('/api/v1/dashboard/recent')
    },
    
    // Lấy xu hướng
    getTrends() {
        return apiClient.get('/api/v1/dashboard/trends')
    },
    
    // Lấy thống kê tuân thủ
    getComplianceStats() {
        return apiClient.get('/api/v1/dashboard/compliance')
    }
}