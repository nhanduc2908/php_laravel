/**
 * NOTIFICATION STORE - Quản lý thông báo realtime
 */

import { defineStore } from 'pinia'

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
        unreadCount: 0,
        globalLoading: false,
        scanProgress: {},
        pollingInterval: null,
    }),
    
    getters: {
        recentNotifications: (state) => state.notifications.slice(0, 5),
        getScanProgress: (state) => (serverId) => state.scanProgress[serverId] || 0,
    },
    
    actions: {
        addNotification(notification) {
            const id = Date.now()
            this.notifications.unshift({
                id,
                ...notification,
                created_at: new Date().toISOString(),
                is_read: false,
            })
            this.unreadCount++
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                this.removeNotification(id)
            }, 5000)
        },
        
        removeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id)
            if (index !== -1) {
                if (!this.notifications[index].is_read) {
                    this.unreadCount--
                }
                this.notifications.splice(index, 1)
            }
        },
        
        markAsRead(id) {
            const notification = this.notifications.find(n => n.id === id)
            if (notification && !notification.is_read) {
                notification.is_read = true
                this.unreadCount--
            }
        },
        
        markAllRead() {
            this.notifications.forEach(n => {
                if (!n.is_read) n.is_read = true
            })
            this.unreadCount = 0
        },
        
        updateScanProgress(serverId, progress) {
            this.scanProgress[serverId] = progress
        },
        
        setLoading(loading) {
            this.globalLoading = loading
        },
        
        startPolling() {
            if (this.pollingInterval) return
            this.pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch('/api/v1/notifications/unread-count')
                    const data = await response.json()
                    if (data.count !== undefined) {
                        this.unreadCount = data.count
                    }
                } catch (error) {
                    console.error('Notification polling error:', error)
                }
            }, 30000)
        },
        
        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval)
                this.pollingInterval = null
            }
        },
        
        clearAll() {
            this.notifications = []
            this.unreadCount = 0
        },
    },
})