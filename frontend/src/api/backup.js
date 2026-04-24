/**
 * Backup API
 * Sao lưu và phục hồi dữ liệu
 */

import apiClient from './client'

export const backupApi = {
    // Tạo backup
    createBackup(type = 'database') {
        return apiClient.post('/api/v1/backup/create', { type })
    },
    
    // Phục hồi backup
    restoreBackup(backupId) {
        return apiClient.post('/api/v1/backup/restore', { backup_id: backupId })
    },
    
    // Lấy danh sách backup
    listBackups() {
        return apiClient.get('/api/v1/backup/list')
    },
    
    // Tải backup
    downloadBackup(id) {
        return apiClient.get(`/api/v1/backup/download/${id}`, {
            responseType: 'blob'
        })
    },
    
    // Xóa backup
    deleteBackup(id) {
        return apiClient.delete(`/api/v1/backup/${id}`)
    }
}