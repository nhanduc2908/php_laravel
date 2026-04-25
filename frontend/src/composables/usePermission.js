/**
 * usePermission - Composable for permission checking
 */

import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

export function usePermission() {
    const authStore = useAuthStore()
    
    const permissions = computed(() => authStore.user?.permissions || [])
    const isSuperAdmin = computed(() => authStore.user?.role === 'super_admin')
    
    const can = (permission) => {
        if (isSuperAdmin.value) return true
        return permissions.value.includes(permission)
    }
    
    const canAny = (permissionList) => {
        if (isSuperAdmin.value) return true
        return permissionList.some(p => permissions.value.includes(p))
    }
    
    const canAll = (permissionList) => {
        if (isSuperAdmin.value) return true
        return permissionList.every(p => permissions.value.includes(p))
    }
    
    const canViewUsers = () => can('view_users')
    const canCreateUsers = () => can('create_users')
    const canEditUsers = () => can('edit_users')
    const canDeleteUsers = () => can('delete_users')
    const canViewServers = () => can('view_servers')
    const canManageServers = () => can('manage_servers')
    const canRunAssessments = () => can('run_assessments')
    const canViewReports = () => can('view_reports')
    const canManageFiles = () => can('manage_files')
    const canViewAudit = () => can('view_audit')
    const canManageSettings = () => can('manage_settings')
    
    return {
        can,
        canAny,
        canAll,
        canViewUsers,
        canCreateUsers,
        canEditUsers,
        canDeleteUsers,
        canViewServers,
        canManageServers,
        canRunAssessments,
        canViewReports,
        canManageFiles,
        canViewAudit,
        canManageSettings,
    }
}