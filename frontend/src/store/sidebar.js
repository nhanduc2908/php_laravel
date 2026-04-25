/**
 * SIDEBAR STORE - Quản lý trạng thái Sidebar
 * Điều khiển ẩn/hiện, thu nhỏ/mở rộng sidebar
 */

import { defineStore } from 'pinia'

export const useSidebarStore = defineStore('sidebar', {
    state: () => ({
        isCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        isHidden: localStorage.getItem('sidebar_hidden') === 'true',
        isMobileOpen: false,
        windowWidth: typeof window !== 'undefined' ? window.innerWidth : 1200,
    }),
    
    getters: {
        sidebarClass: (state) => ({
            collapsed: state.isCollapsed,
            hidden: state.isHidden,
            'mobile-open': state.isMobileOpen,
        }),
        sidebarWidth: (state) => {
            if (state.isHidden) return 0
            if (state.isCollapsed) return 70
            return 280
        },
        isMobile: (state) => state.windowWidth < 768,
    },
    
    actions: {
        toggleCollapse() {
            this.isCollapsed = !this.isCollapsed
            localStorage.setItem('sidebar_collapsed', this.isCollapsed)
        },
        
        toggleHide() {
            this.isHidden = !this.isHidden
            localStorage.setItem('sidebar_hidden', this.isHidden)
        },
        
        setCollapsed(value) {
            this.isCollapsed = value
            localStorage.setItem('sidebar_collapsed', value)
        },
        
        setHidden(value) {
            this.isHidden = value
            localStorage.setItem('sidebar_hidden', value)
        },
        
        toggleMobile() {
            this.isMobileOpen = !this.isMobileOpen
        },
        
        closeMobile() {
            this.isMobileOpen = false
        },
        
        handleResize() {
            this.windowWidth = window.innerWidth
            if (this.windowWidth >= 768) {
                this.isMobileOpen = false
            }
        },
        
        reset() {
            this.isCollapsed = false
            this.isHidden = false
            this.isMobileOpen = false
        },
    },
})