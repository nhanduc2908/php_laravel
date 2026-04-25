/**
 * ALERT STORE - Quản lý cảnh báo
 */

import { defineStore } from 'pinia'
import { alertsApi } from '@/api/alerts'
import { useToast } from 'vue-toastification'

export const useAlertStore = defineStore('alert', {
    state: () => ({
        alerts: [],
        unreadCount: 0,
        pagination: {
            currentPage: 1,
            totalPages: 1,
            total: 0,
            perPage: 20,
        },
        isLoading: false,
    }),
    
    getters: {
        criticalAlerts: (state) => state.alerts.filter(a => a.severity === 'critical'),
        highAlerts: (state) => state.alerts.filter(a => a.severity === 'high'),
        getUnreadAlerts: (state) => state.alerts.filter(a => !a.is_read && !a.is_resolved),
    },
    
    actions: {
        async fetchAlerts(params = {}) {
            this.isLoading = true
            try {
                const response = await alertsApi.getAlerts(params)
                if (response.status === 'success') {
                    this.alerts = response.data.data
                    this.unreadCount = this.alerts.filter(a => !a.is_read && !a.is_resolved).length
                    this.pagination = {
                        currentPage: response.data.current_page,
                        totalPages: response.data.last_page,
                        total: response.data.total,
                        perPage: response.data.per_page,
                    }
                    return { success: true, data: this.alerts, pagination: this.pagination }
                }
            } catch (error) {
                console.error('Fetch alerts failed:', error)
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async markRead(id) {
            try {
                const response = await alertsApi.markRead(id)
                if (response.status === 'success') {
                    await this.fetchAlerts()
                    const toast = useToast()
                    toast.success('Đã đánh dấu đã đọc')
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Đánh dấu thất bại')
                return { success: false, error }
            }
        },
        
        async bulkMarkRead(ids) {
            try {
                const response = await alertsApi.bulkMarkRead(ids)
                if (response.status === 'success') {
                    await this.fetchAlerts()
                    const toast = useToast()
                    toast.success('Đã đánh dấu tất cả đã đọc')
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Đánh dấu thất bại')
                return { success: false, error }
            }
        },
        
        async resolve(id) {
            try {
                const response = await alertsApi.resolve(id)
                if (response.status === 'success') {
                    await this.fetchAlerts()
                    const toast = useToast()
                    toast.success('Đã giải quyết cảnh báo')
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Giải quyết thất bại')
                return { success: false, error }
            }
        },
    },
})