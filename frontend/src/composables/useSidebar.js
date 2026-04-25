/**
 * useSidebar - Composable for sidebar management
 */

import { computed } from 'vue'
import { useSidebarStore } from '@/stores/sidebar'

export function useSidebar() {
    const sidebarStore = useSidebarStore()
    
    const isCollapsed = computed(() => sidebarStore.isCollapsed)
    const isHidden = computed(() => sidebarStore.isHidden)
    const isMobileOpen = computed(() => sidebarStore.isMobileOpen)
    const isMobile = computed(() => sidebarStore.isMobile)
    const sidebarWidth = computed(() => sidebarStore.sidebarWidth)
    const sidebarClass = computed(() => sidebarStore.sidebarClass)
    
    const toggleCollapse = () => sidebarStore.toggleCollapse()
    const toggleHide = () => sidebarStore.toggleHide()
    const toggleMobile = () => sidebarStore.toggleMobile()
    const closeMobile = () => sidebarStore.closeMobile()
    const setCollapsed = (value) => sidebarStore.setCollapsed(value)
    const setHidden = (value) => sidebarStore.setHidden(value)
    
    const handleResize = () => sidebarStore.handleResize()
    
    return {
        // State
        isCollapsed,
        isHidden,
        isMobileOpen,
        isMobile,
        sidebarWidth,
        sidebarClass,
        // Methods
        toggleCollapse,
        toggleHide,
        toggleMobile,
        closeMobile,
        setCollapsed,
        setHidden,
        handleResize,
    }
}