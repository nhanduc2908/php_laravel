/**
 * Settings API
 * Cài đặt hệ thống
 */

import apiClient from './client'

export const settingsApi = {
    // Lấy tất cả settings
    getSettings() {
        return apiClient.get('/api/v1/settings')
    },
    
    // Cập nhật settings
    updateSettings(data) {
        return apiClient.put('/api/v1/settings', data)
    },
    
    // Lấy settings theo group
    getSettingsByGroup(group) {
        return apiClient.get(`/api/v1/settings/group/${group}`)
    },
    
    // Reset settings
    resetSettings(group) {
        return apiClient.post(`/api/v1/settings/reset/${group}`)
    }
}