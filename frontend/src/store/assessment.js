/**
 * ASSESSMENT STORE - Quản lý đánh giá an ninh
 */

import { defineStore } from 'pinia'
import { assessmentsApi } from '@/api/assessments'
import { useToast } from 'vue-toastification'

export const useAssessmentStore = defineStore('assessment', {
    state: () => ({
        assessments: [],
        currentAssessment: null,
        currentResult: null,
        pagination: {
            currentPage: 1,
            totalPages: 1,
            total: 0,
            perPage: 15,
        },
        isLoading: false,
        isRunning: false,
    }),
    
    getters: {
        getAssessmentById: (state) => (id) => state.assessments.find(a => a.id === id),
        averageScore: (state) => {
            if (state.assessments.length === 0) return 0
            const sum = state.assessments.reduce((acc, a) => acc + (a.total_score || 0), 0)
            return Math.round(sum / state.assessments.length)
        },
    },
    
    actions: {
        async fetchAssessments(params = {}) {
            this.isLoading = true
            try {
                const response = await assessmentsApi.getAssessmentHistory(params)
                if (response.status === 'success') {
                    this.assessments = response.data.data
                    this.pagination = {
                        currentPage: response.data.current_page,
                        totalPages: response.data.last_page,
                        total: response.data.total,
                        perPage: response.data.per_page,
                    }
                    return { success: true, data: this.assessments, pagination: this.pagination }
                }
            } catch (error) {
                console.error('Fetch assessments failed:', error)
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async runAssessment(data) {
            this.isRunning = true
            try {
                const response = await assessmentsApi.runAssessment(data)
                if (response.status === 'success') {
                    this.currentResult = response.data
                    const toast = useToast()
                    toast.success('Đánh giá hoàn tất')
                    return { success: true, data: response.data }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Đánh giá thất bại')
                return { success: false, error }
            } finally {
                this.isRunning = false
            }
        },
        
        async getResult(id) {
            this.isLoading = true
            try {
                const response = await assessmentsApi.getAssessmentResult(id)
                if (response.status === 'success') {
                    this.currentResult = response.data
                    return response.data
                }
            } catch (error) {
                console.error('Fetch result failed:', error)
            } finally {
                this.isLoading = false
            }
            return null
        },
        
        async getCompliance(serverId) {
            try {
                const response = await assessmentsApi.getComplianceStatus(serverId)
                if (response.status === 'success') {
                    return response.data
                }
            } catch (error) {
                console.error('Fetch compliance failed:', error)
            }
            return null
        },
        
        async exportResult(id, format = 'pdf') {
            try {
                const response = await assessmentsApi.exportAssessment(id, format)
                const url = window.URL.createObjectURL(new Blob([response]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', `assessment_${id}.${format}`)
                document.body.appendChild(link)
                link.click()
                link.remove()
                window.URL.revokeObjectURL(url)
                const toast = useToast()
                toast.success('Xuất báo cáo thành công')
                return { success: true }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Xuất thất bại')
                return { success: false, error }
            }
        },
        
        async deleteAssessment(id) {
            // Implementation for delete
        },
    },
})