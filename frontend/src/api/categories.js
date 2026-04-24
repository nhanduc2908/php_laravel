/**
 * Categories API
 * Quản lý danh mục (17 categories)
 */

import apiClient from './client'

export const categoriesApi = {
    // Lấy danh sách categories
    getCategories() {
        return apiClient.get('/api/v1/categories')
    },
    
    // Lấy chi tiết category
    getCategory(id) {
        return apiClient.get(`/api/v1/categories/${id}`)
    },
    
    // Lấy criteria theo category
    getCriteriaByCategory(id) {
        return apiClient.get(`/api/v1/categories/${id}/criteria`)
    },
    
    // Lấy cây categories
    getCategoryTree() {
        return apiClient.get('/api/v1/categories/tree')
    }
}
