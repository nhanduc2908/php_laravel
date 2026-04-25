/**
 * SERVER STORE - Quản lý máy chủ
 */

import { defineStore } from 'pinia'
import { serversApi } from '@/api/servers'
import { useToast } from 'vue-toastification'

export const useServerStore = defineStore('server', {
    state: () => ({
        servers: [],
        currentServer: null,
        pagination: {
            currentPage: 1,
            totalPages: 1,
            total: 0,
            perPage: 15,
        },
        isLoading: false,
        scanProgress: {},
    }),
    
    getters: {
        getServerById: (state) => (id) => state.servers.find(s => s.id === id),
        activeServers: (state) => state.servers.filter(s => s.status === 'active'),
        getScanProgress: (state) => (id) => state.scanProgress[id] || 0,
    },
    
    actions: {
        async fetchServers(params = {}) {
            this.isLoading = true
            try {
                const response = await serversApi.getServers(params)
                if (response.status === 'success') {
                    this.servers = response.data.data
                    this.pagination = {
                        currentPage: response.data.current_page,
                        totalPages: response.data.last_page,
                        total: response.data.total,
                        perPage: response.data.per_page,
                    }
                    return { success: true, data: this.servers, pagination: this.pagination }
                }
            } catch (error) {
                console.error('Fetch servers failed:', error)
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async getServer(id) {
            try {
                const response = await serversApi.getServer(id)
                if (response.status === 'success') {
                    this.currentServer = response.data
                    return response.data
                }
            } catch (error) {
                console.error('Fetch server failed:', error)
            }
            return null
        },
        
        async createServer(data) {
            this.isLoading = true
            try {
                const response = await serversApi.createServer(data)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Thêm máy chủ thành công')
                    await this.fetchServers()
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
        
        async updateServer(id, data) {
            this.isLoading = true
            try {
                const response = await serversApi.updateServer(id, data)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Cập nhật thành công')
                    await this.fetchServers()
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
        
        async deleteServer(id) {
            try {
                const response = await serversApi.deleteServer(id)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Xóa máy chủ thành công')
                    await this.fetchServers()
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Xóa thất bại')
                return { success: false, error }
            }
        },
        
        async testConnection(id) {
            try {
                const response = await serversApi.testConnection(id)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success(response.message || 'Kết nối thành công')
                    return { success: true, data: response.data }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Kết nối thất bại')
                return { success: false, error }
            }
        },
        
        async scanServer(id) {
            try {
                const response = await serversApi.scanServer(id)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Đã bắt đầu quét máy chủ')
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Quét thất bại')
                return { success: false, error }
            }
        },
        
        updateScanProgress(serverId, progress) {
            this.scanProgress[serverId] = progress
        },
    },
})