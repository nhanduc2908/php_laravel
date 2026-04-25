/**
 * CRITERIA STORE - Quản lý tiêu chí đánh giá
 */

import { defineStore } from 'pinia'
import { criteriaApi } from '@/api/criteria'
import { useToast } from 'vue-toastification'

export const useCriteriaStore = defineStore('criteria', {
    state: () => ({
        criteria: [],
        categories: [],
        currentCriterion: null,
        pagination: {
            currentPage: 1,
            totalPages: 1,
            total: 0,
            perPage: 20,
        },
        isLoading: false,
    }),
    
    getters: {
        getCriteriaByCategory: (state) => (categoryId) => state.criteria.filter(c => c.category_id === categoryId),
        getTotalWeight: (state) => state.criteria.reduce((sum, c) => sum + c.weight, 0),
    },
    
    actions: {
        async fetchCriteria(params = {}) {
            this.isLoading = true
            try {
                const response = await criteriaApi.getCriteria(params)
                if (response.status === 'success') {
                    this.criteria = response.data.data
                    this.pagination = {
                        currentPage: response.data.current_page,
                        totalPages: response.data.last_page,
                        total: response.data.total,
                        perPage: response.data.per_page,
                    }
                    return { success: true, data: this.criteria, pagination: this.pagination }
                }
            } catch (error) {
                console.error('Fetch criteria failed:', error)
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async getCriterion(id) {
            try {
                const response = await criteriaApi.getCriteriaItem(id)
                if (response.status === 'success') {
                    this.currentCriterion = response.data
                    return response.data
                }
            } catch (error) {
                console.error('Fetch criterion failed:', error)
            }
            return null
        },
        
        async createCriteria(data) {
            this.isLoading = true
            try {
                const response = await criteriaApi.createCriteria(data)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Thêm tiêu chí thành công')
                    await this.fetchCriteria()
                    return { success: true, data: response.data }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Thêm thất bại')
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async updateCriteria(id, data) {
            this.isLoading = true
            try {
                const response = await criteriaApi.updateCriteria(id, data)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Cập nhật thành công')
                    await this.fetchCriteria()
                    return { success: true, data: response.data }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Cập nhật thất bại')
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async deleteCriteria(id) {
            try {
                const response = await criteriaApi.deleteCriteria(id)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Xóa tiêu chí thành công')
                    await this.fetchCriteria()
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Xóa thất bại')
                return { success: false, error }
            }
        },
        
        async importCriteria(file) {
            this.isLoading = true
            try {
                const response = await criteriaApi.importCriteria(file)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Import thành công')
                    await this.fetchCriteria()
                    return { success: true, data: response.data }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Import thất bại')
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async exportCriteria() {
            try {
                const response = await criteriaApi.exportCriteria()
                const url = window.URL.createObjectURL(new Blob([response]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', 'criteria_export.xlsx')
                document.body.appendChild(link)
                link.click()
                link.remove()
                window.URL.revokeObjectURL(url)
                const toast = useToast()
                toast.success('Export thành công')
                return { success: true }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Export thất bại')
                return { success: false, error }
            }
        },
    },
})