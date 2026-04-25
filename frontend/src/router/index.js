/**
 * VUE ROUTER CONFIGURATION
 * Định nghĩa tất cả routes cho ứng dụng
 */

import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

// ==================== AUTH ROUTES ====================
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'
import ForgotPassword from '@/views/auth/ForgotPassword.vue'
import ResetPassword from '@/views/auth/ResetPassword.vue'
import TwoFactor from '@/views/auth/TwoFactor.vue'

// ==================== SUPER ADMIN ROUTES ====================
import SuperDashboard from '@/views/super-admin/Dashboard.vue'
import SuperUsers from '@/views/super-admin/Users.vue'
import SuperRoles from '@/views/super-admin/Roles.vue'
import SuperServers from '@/views/super-admin/Servers.vue'
import SuperCategories from '@/views/super-admin/Categories.vue'
import SuperCriteria from '@/views/super-admin/Criteria.vue'
import SuperAssessments from '@/views/super-admin/Assessments.vue'
import SuperAssessmentFiles from '@/views/super-admin/AssessmentFiles.vue'
import SuperVulnerabilities from '@/views/super-admin/Vulnerabilities.vue'
import SuperAlerts from '@/views/super-admin/Alerts.vue'
import SuperReports from '@/views/super-admin/Reports.vue'
import SuperBackup from '@/views/super-admin/Backup.vue'
import SuperAudit from '@/views/super-admin/Audit.vue'
import SuperSettings from '@/views/super-admin/Settings.vue'
import TestingIndex from '@/views/super-admin/testing/Index.vue'
import TestingRunner from '@/views/super-admin/testing/Runner.vue'
import TestingResults from '@/views/super-admin/testing/Results.vue'

// ==================== ADMIN ROUTES ====================
import AdminDashboard from '@/views/admin/Dashboard.vue'
import AdminUsers from '@/views/admin/Users.vue'
import AdminRoles from '@/views/admin/Roles.vue'
import AdminServers from '@/views/admin/Servers.vue'
import AdminCriteria from '@/views/admin/Criteria.vue'
import AdminAssessments from '@/views/admin/Assessments.vue'
import AdminAssessmentFiles from '@/views/admin/AssessmentFiles.vue'
import AdminVulnerabilities from '@/views/admin/Vulnerabilities.vue'
import AdminAlerts from '@/views/admin/Alerts.vue'
import AdminReports from '@/views/admin/Reports.vue'
import AdminBackup from '@/views/admin/Backup.vue'
import AdminSettings from '@/views/admin/Settings.vue'

// ==================== SECURITY OFFICER ROUTES ====================
import SecurityDashboard from '@/views/security-officer/Dashboard.vue'
import SecurityServers from '@/views/security-officer/Servers.vue'
import SecurityAssessments from '@/views/security-officer/Assessments.vue'
import SecurityAssessmentFiles from '@/views/security-officer/AssessmentFiles.vue'
import SecurityVulnerabilities from '@/views/security-officer/Vulnerabilities.vue'
import SecurityAlerts from '@/views/security-officer/Alerts.vue'
import SecurityReports from '@/views/security-officer/Reports.vue'

// ==================== VIEWER ROUTES ====================
import ViewerDashboard from '@/views/viewer/Dashboard.vue'
import ViewerReports from '@/views/viewer/Reports.vue'
import ViewerComplianceStatus from '@/views/viewer/ComplianceStatus.vue'
import ViewerServerList from '@/views/viewer/ServerList.vue'

// ==================== AUDITOR ROUTES ====================
import AuditorDashboard from '@/views/auditor/Dashboard.vue'
import AuditorAuditLogs from '@/views/auditor/AuditLogs.vue'
import AuditorUserActivities from '@/views/auditor/UserActivities.vue'
import AuditorReports from '@/views/auditor/Reports.vue'

// ==================== COMMON PAGES ====================
import NotFound from '@/views/common/NotFound.vue'
import Forbidden from '@/views/common/Forbidden.vue'
import ServerError from '@/views/common/ServerError.vue'
import Maintenance from '@/views/common/Maintenance.vue'

// ==================== ROUTE CONFIGURATION ====================
const routes = [
    // Auth routes
    { path: '/login', name: 'login', component: Login, meta: { guest: true, title: 'Đăng nhập' } },
    { path: '/register', name: 'register', component: Register, meta: { guest: true, title: 'Đăng ký' } },
    { path: '/forgot-password', name: 'forgot-password', component: ForgotPassword, meta: { guest: true, title: 'Quên mật khẩu' } },
    { path: '/reset-password', name: 'reset-password', component: ResetPassword, meta: { guest: true, title: 'Đặt lại mật khẩu' } },
    { path: '/2fa', name: '2fa', component: TwoFactor, meta: { guest: true, title: 'Xác thực 2FA' } },
    
    // Super Admin routes
    { path: '/super-admin', redirect: '/super-admin/dashboard' },
    { path: '/super-admin/dashboard', name: 'super-dashboard', component: SuperDashboard, meta: { role: 'super_admin', title: 'Dashboard' } },
    { path: '/super-admin/users', name: 'super-users', component: SuperUsers, meta: { role: 'super_admin', title: 'Quản lý người dùng' } },
    { path: '/super-admin/roles', name: 'super-roles', component: SuperRoles, meta: { role: 'super_admin', title: 'Quản lý vai trò' } },
    { path: '/super-admin/servers', name: 'super-servers', component: SuperServers, meta: { role: 'super_admin', title: 'Quản lý máy chủ' } },
    { path: '/super-admin/categories', name: 'super-categories', component: SuperCategories, meta: { role: 'super_admin', title: 'Quản lý danh mục' } },
    { path: '/super-admin/criteria', name: 'super-criteria', component: SuperCriteria, meta: { role: 'super_admin', title: 'Quản lý tiêu chí' } },
    { path: '/super-admin/assessments', name: 'super-assessments', component: SuperAssessments, meta: { role: 'super_admin', title: 'Quản lý đánh giá' } },
    { path: '/super-admin/files', name: 'super-files', component: SuperAssessmentFiles, meta: { role: 'super_admin', title: 'Quản lý tệp' } },
    { path: '/super-admin/vulnerabilities', name: 'super-vulnerabilities', component: SuperVulnerabilities, meta: { role: 'super_admin', title: 'Quản lý lỗ hổng' } },
    { path: '/super-admin/alerts', name: 'super-alerts', component: SuperAlerts, meta: { role: 'super_admin', title: 'Quản lý cảnh báo' } },
    { path: '/super-admin/reports', name: 'super-reports', component: SuperReports, meta: { role: 'super_admin', title: 'Quản lý báo cáo' } },
    { path: '/super-admin/backup', name: 'super-backup', component: SuperBackup, meta: { role: 'super_admin', title: 'Sao lưu & Phục hồi' } },
    { path: '/super-admin/audit', name: 'super-audit', component: SuperAudit, meta: { role: 'super_admin', title: 'Audit Logs' } },
    { path: '/super-admin/settings', name: 'super-settings', component: SuperSettings, meta: { role: 'super_admin', title: 'Cài đặt hệ thống' } },
    { path: '/super-admin/testing', name: 'testing-index', component: TestingIndex, meta: { role: 'super_admin', title: 'Testing Dashboard' } },
    { path: '/super-admin/testing/runner', name: 'testing-runner', component: TestingRunner, meta: { role: 'super_admin', title: 'Test Runner' } },
    { path: '/super-admin/testing/results', name: 'testing-results', component: TestingResults, meta: { role: 'super_admin', title: 'Test Results' } },
    
    // Admin routes
    { path: '/admin', redirect: '/admin/dashboard' },
    { path: '/admin/dashboard', name: 'admin-dashboard', component: AdminDashboard, meta: { role: 'admin', title: 'Dashboard' } },
    { path: '/admin/users', name: 'admin-users', component: AdminUsers, meta: { role: 'admin', title: 'Quản lý người dùng' } },
    { path: '/admin/roles', name: 'admin-roles', component: AdminRoles, meta: { role: 'admin', title: 'Quản lý vai trò' } },
    { path: '/admin/servers', name: 'admin-servers', component: AdminServers, meta: { role: 'admin', title: 'Quản lý máy chủ' } },
    { path: '/admin/criteria', name: 'admin-criteria', component: AdminCriteria, meta: { role: 'admin', title: 'Quản lý tiêu chí' } },
    { path: '/admin/assessments', name: 'admin-assessments', component: AdminAssessments, meta: { role: 'admin', title: 'Quản lý đánh giá' } },
    { path: '/admin/files', name: 'admin-files', component: AdminAssessmentFiles, meta: { role: 'admin', title: 'Quản lý tệp' } },
    { path: '/admin/vulnerabilities', name: 'admin-vulnerabilities', component: AdminVulnerabilities, meta: { role: 'admin', title: 'Quản lý lỗ hổng' } },
    { path: '/admin/alerts', name: 'admin-alerts', component: AdminAlerts, meta: { role: 'admin', title: 'Quản lý cảnh báo' } },
    { path: '/admin/reports', name: 'admin-reports', component: AdminReports, meta: { role: 'admin', title: 'Quản lý báo cáo' } },
    { path: '/admin/backup', name: 'admin-backup', component: AdminBackup, meta: { role: 'admin', title: 'Sao lưu' } },
    { path: '/admin/settings', name: 'admin-settings', component: AdminSettings, meta: { role: 'admin', title: 'Cài đặt' } },
    
    // Security Officer routes
    { path: '/security', redirect: '/security/dashboard' },
    { path: '/security/dashboard', name: 'security-dashboard', component: SecurityDashboard, meta: { role: 'security_officer', title: 'Dashboard' } },
    { path: '/security/servers', name: 'security-servers', component: SecurityServers, meta: { role: 'security_officer', title: 'Máy chủ' } },
    { path: '/security/assessments', name: 'security-assessments', component: SecurityAssessments, meta: { role: 'security_officer', title: 'Đánh giá' } },
    { path: '/security/files', name: 'security-files', component: SecurityAssessmentFiles, meta: { role: 'security_officer', title: 'Tệp đánh giá' } },
    { path: '/security/vulnerabilities', name: 'security-vulnerabilities', component: SecurityVulnerabilities, meta: { role: 'security_officer', title: 'Lỗ hổng' } },
    { path: '/security/alerts', name: 'security-alerts', component: SecurityAlerts, meta: { role: 'security_officer', title: 'Cảnh báo' } },
    { path: '/security/reports', name: 'security-reports', component: SecurityReports, meta: { role: 'security_officer', title: 'Báo cáo' } },
    
    // Viewer routes
    { path: '/viewer', redirect: '/viewer/dashboard' },
    { path: '/viewer/dashboard', name: 'viewer-dashboard', component: ViewerDashboard, meta: { role: 'viewer', title: 'Dashboard' } },
    { path: '/viewer/reports', name: 'viewer-reports', component: ViewerReports, meta: { role: 'viewer', title: 'Báo cáo' } },
    { path: '/viewer/compliance', name: 'viewer-compliance', component: ViewerComplianceStatus, meta: { role: 'viewer', title: 'Trạng thái tuân thủ' } },
    { path: '/viewer/servers', name: 'viewer-servers', component: ViewerServerList, meta: { role: 'viewer', title: 'Danh sách máy chủ' } },
    
    // Auditor routes
    { path: '/auditor', redirect: '/auditor/dashboard' },
    { path: '/auditor/dashboard', name: 'auditor-dashboard', component: AuditorDashboard, meta: { role: 'auditor', title: 'Audit Dashboard' } },
    { path: '/auditor/logs', name: 'auditor-logs', component: AuditorAuditLogs, meta: { role: 'auditor', title: 'Audit Logs' } },
    { path: '/auditor/activities', name: 'auditor-activities', component: AuditorUserActivities, meta: { role: 'auditor', title: 'User Activities' } },
    { path: '/auditor/reports', name: 'auditor-reports', component: AuditorReports, meta: { role: 'auditor', title: 'Báo cáo kiểm toán' } },
    
    // Common routes (accessible by all authenticated users)
    { path: '/dashboard', redirect: '/super-admin/dashboard' }, // Will redirect based on role
    { path: '/', redirect: '/login' },
    
    // Error pages
    { path: '/403', name: 'forbidden', component: Forbidden, meta: { title: '403 - Forbidden' } },
    { path: '/500', name: 'server-error', component: ServerError, meta: { title: '500 - Server Error' } },
    { path: '/maintenance', name: 'maintenance', component: Maintenance, meta: { title: 'Maintenance' } },
    { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound, meta: { title: '404 - Not Found' } },
]

// ==================== CREATE ROUTER ====================
const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) return savedPosition
        else return { top: 0 }
    },
})

// ==================== NAVIGATION GUARDS ====================
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore()
    const toast = useToast()
    
    // Update document title
    document.title = to.meta.title ? `${to.meta.title} | Security Assessment Platform` : 'Security Assessment Platform'
    
    // Check maintenance mode
    const isMaintenance = false // Can be set from config
    if (isMaintenance && to.name !== 'maintenance') {
        return next({ name: 'maintenance' })
    }
    
    // Check authentication
    const requiresAuth = !to.meta.guest
    const isAuthenticated = authStore.isAuthenticated
    
    // If route requires auth and user is not authenticated
    if (requiresAuth && !isAuthenticated) {
        toast.warning('Vui lòng đăng nhập để tiếp tục')
        return next({ name: 'login', query: { redirect: to.fullPath } })
    }
    
    // If user is authenticated and tries to access guest page
    if (to.meta.guest && isAuthenticated) {
        // Redirect to role-based dashboard
        const role = authStore.user?.role
        if (role === 'super_admin') return next('/super-admin/dashboard')
        if (role === 'admin') return next('/admin/dashboard')
        if (role === 'security_officer') return next('/security/dashboard')
        if (role === 'viewer') return next('/viewer/dashboard')
        if (role === 'auditor') return next('/auditor/dashboard')
        return next('/dashboard')
    }
    
    // Check role-based access
    if (to.meta.role && isAuthenticated) {
        const userRole = authStore.user?.role
        const allowedRoles = Array.isArray(to.meta.role) ? to.meta.role : [to.meta.role]
        
        if (!allowedRoles.includes(userRole)) {
            toast.error('Bạn không có quyền truy cập trang này')
            return next('/403')
        }
    }
    
    // Refresh token if needed
    if (isAuthenticated && authStore.isTokenExpiringSoon) {
        try {
            await authStore.refreshToken()
        } catch (error) {
            authStore.logout()
            return next({ name: 'login' })
        }
    }
    
    next()
})

// Handle errors
router.onError((error) => {
    console.error('Router error:', error)
})

export default router