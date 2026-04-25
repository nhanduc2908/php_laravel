/**
 * USER STORE - Quản lý người dùng
 */

import { defineStore } from 'pinia'
import { usersApi } from '@/api/users'
import { useToast } from 'vue-toastification'

export const useUserStore = defineStore('user', {
    state: () => ({
        users: [],
        currentUser: null,
        pagination: {
            currentPage: 1,
            totalPages: 1,
            total: 0,
            perPage: 15,
        },
        isLoading: false,
    }),
    
    getters: {
        getUserById: (state) => (id) => state.users.find(u => u.id === id),
    },
    
    actions: {
        async fetchUsers(params = {}) {
            this.isLoading = true
            try {
                const response = await usersApi.getUsers(params)
                if (response.status === 'success') {
                    this.users = response.data.data
                    this.pagination = {
                        currentPage: response.data.current_page,
                        totalPages: response.data.last_page,
                        total: response.data.total,
                        perPage: response.data.per_page,
                    }
                    return { success: true, data: this.users, pagination: this.pagination }
                }
            } catch (error) {
                console.error('Fetch users failed:', error)
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async getUser(id) {
            try {
                const response = await usersApi.getUser(id)
                if (response.status === 'success') {
                    this.currentUser = response.data
                    return response.data
                }
            } catch (error) {
                console.error('Fetch user failed:', error)
            }
            return null
        },
        
        async createUser(data) {
            this.isLoading = true
            try {
                const response = await usersApi.createUser(data)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Tạo người dùng thành công')
                    await this.fetchUsers()
                    return { success: true, data: response.data }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Tạo thất bại')
                return { success: false, error }
            } finally {
                this.isLoading = false
            }
        },
        
        async updateUser(id, data) {
            this.isLoading = true
            try {
                const response = await usersApi.updateUser(id, data)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Cập nhật thành công')
                    await this.fetchUsers()
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
        
        async deleteUser(id) {
            try {
                const response = await usersApi.deleteUser(id)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Xóa người dùng thành công')
                    await this.fetchUsers()
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Xóa thất bại')
                return { success: false, error }
            }
        },
        
        async assignRole(userId, roleId) {
            try {
                const response = await usersApi.assignRole(userId, roleId)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Phân quyền thành công')
                    await this.fetchUsers()
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Phân quyền thất bại')
                return { success: false, error }
            }
        },
        
        async resetPassword(id) {
            try {
                const response = await usersApi.resetPassword(id)
                if (response.status === 'success') {
                    const toast = useToast()
                    toast.success('Đã gửi email reset mật khẩu')
                    return { success: true }
                }
            } catch (error) {
                const toast = useToast()
                toast.error(error.message || 'Gửi email thất bại')
                return { success: false, error }
            }
        },
    },
})