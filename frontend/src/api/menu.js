/**
 * Menu API
 * Lấy menu động theo role
 */

import apiClient from './client'

export const menuApi = {
    // Lấy menu chính
    getMenu() {
        return apiClient.get('/api/v1/menu')
    },
    
    // Lấy sidebar menu
    getSidebar() {
        return apiClient.get('/api/v1/menu/sidebar')
    },
    
    // Lấy permissions
    getPermissions() {
        return apiClient.get('/api/v1/menu/permissions')
    }
}