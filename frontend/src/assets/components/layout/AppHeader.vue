<template>
    <header class="app-header" :class="{ 'header-collapsed': sidebarStore.isCollapsed }">
        <div class="header-left">
            <!-- Toggle Sidebar Button -->
            <button class="header-btn" @click="toggleSidebar" :title="sidebarStore.isCollapsed ? 'Mở rộng' : 'Thu nhỏ'">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Hide Sidebar Button -->
            <button class="header-btn" @click="toggleHideSidebar" :title="sidebarStore.isHidden ? 'Hiện sidebar' : 'Ẩn sidebar'">
                <i :class="sidebarStore.isHidden ? 'fas fa-chevron-right' : 'fas fa-chevron-left'"></i>
            </button>
            
            <!-- Logo (hiển thị khi sidebar thu nhỏ) -->
            <div class="logo" v-show="sidebarStore.isCollapsed && !sidebarStore.isHidden">
                <img src="/assets/images/logo.svg" alt="Logo" class="logo-img">
            </div>
        </div>
        
        <div class="header-center">
            <!-- Page Title -->
            <h1 class="page-title">{{ pageTitle }}</h1>
        </div>
        
        <div class="header-right">
            <!-- Search -->
            <div class="search-bar" v-if="showSearch">
                <i class="fas fa-search"></i>
                <input type="text" v-model="searchQuery" placeholder="Tìm kiếm..." @keyup.enter="onSearch">
            </div>
            
            <!-- Language Switcher -->
            <div class="dropdown">
                <button class="header-btn" @click="toggleLanguageDropdown">
                    <i class="fas fa-globe"></i>
                    <span class="lang-code">{{ currentLocale.toUpperCase() }}</span>
                </button>
                <div class="dropdown-menu" v-show="showLanguageDropdown">
                    <a href="#" class="dropdown-item" @click.prevent="switchLanguage('en')">
                        <img src="/assets/images/flags/en.svg" alt="EN"> English
                    </a>
                    <a href="#" class="dropdown-item" @click.prevent="switchLanguage('vi')">
                        <img src="/assets/images/flags/vi.svg" alt="VI"> Tiếng Việt
                    </a>
                    <a href="#" class="dropdown-item" @click.prevent="switchLanguage('ja')">
                        <img src="/assets/images/flags/ja.svg" alt="JA"> 日本語
                    </a>
                </div>
            </div>
            
            <!-- Theme Toggle -->
            <button class="header-btn" @click="toggleTheme">
                <i :class="isDark ? 'fas fa-sun' : 'fas fa-moon'"></i>
            </button>
            
            <!-- Notifications -->
            <div class="dropdown">
                <button class="header-btn notification-btn" @click="toggleNotificationDropdown">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" v-if="unreadCount > 0">{{ unreadCount }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" v-show="showNotificationDropdown">
                    <div class="dropdown-header">
                        <span>Thông báo</span>
                        <button class="btn-text" @click="markAllRead">Đánh dấu đã đọc</button>
                    </div>
                    <div class="dropdown-list">
                        <div v-for="notif in notifications" :key="notif.id" class="notification-item" :class="{ unread: !notif.is_read }">
                            <div class="notification-icon" :class="notif.type">
                                <i :class="getNotificationIcon(notif.type)"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">{{ notif.title }}</div>
                                <div class="notification-message">{{ notif.message }}</div>
                                <div class="notification-time">{{ formatTime(notif.created_at) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-footer">
                        <router-link to="/notifications">Xem tất cả</router-link>
                    </div>
                </div>
            </div>
            
            <!-- User Menu -->
            <div class="dropdown">
                <button class="user-btn" @click="toggleUserDropdown">
                    <img :src="userAvatar" alt="Avatar" class="avatar">
                    <span class="user-name">{{ userName }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" v-show="showUserDropdown">
                    <router-link to="/profile" class="dropdown-item">
                        <i class="fas fa-user"></i> Hồ sơ
                    </router-link>
                    <router-link to="/profile/security" class="dropdown-item">
                        <i class="fas fa-shield-alt"></i> Bảo mật
                    </router-link>
                    <router-link to="/settings" class="dropdown-item">
                        <i class="fas fa-cog"></i> Cài đặt
                    </router-link>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-danger" @click.prevent="logout">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useSidebarStore } from '@/stores/sidebar'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import { useNotificationStore } from '@/stores/notification'
import { useLanguageStore } from '@/stores/language'

const route = useRoute()
const sidebarStore = useSidebarStore()
const authStore = useAuthStore()
const themeStore = useThemeStore()
const notificationStore = useNotificationStore()
const languageStore = useLanguageStore()

// State
const showLanguageDropdown = ref(false)
const showNotificationDropdown = ref(false)
const showUserDropdown = ref(false)
const searchQuery = ref('')

// Computed
const pageTitle = computed(() => route.meta?.title || 'Dashboard')
const showSearch = computed(() => route.meta?.showSearch !== false)
const userName = computed(() => authStore.user?.name || 'Guest')
const userAvatar = computed(() => authStore.user?.avatar || '/assets/images/default-avatar.png')
const isDark = computed(() => themeStore.isDark)
const currentLocale = computed(() => languageStore.currentLocale)
const unreadCount = computed(() => notificationStore.unreadCount)
const notifications = computed(() => notificationStore.notifications.slice(0, 5))

// Methods
const toggleSidebar = () => {
    sidebarStore.toggleCollapse()
}

const toggleHideSidebar = () => {
    sidebarStore.toggleHide()
}

const toggleLanguageDropdown = () => {
    showLanguageDropdown.value = !showLanguageDropdown.value
    showNotificationDropdown.value = false
    showUserDropdown.value = false
}

const toggleNotificationDropdown = () => {
    showNotificationDropdown.value = !showNotificationDropdown.value
    showLanguageDropdown.value = false
    showUserDropdown.value = false
    if (showNotificationDropdown.value) {
        notificationStore.fetchNotifications()
    }
}

const toggleUserDropdown = () => {
    showUserDropdown.value = !showUserDropdown.value
    showLanguageDropdown.value = false
    showNotificationDropdown.value = false
}

const switchLanguage = (locale) => {
    languageStore.setLocale(locale)
    showLanguageDropdown.value = false
}

const toggleTheme = () => {
    themeStore.toggleTheme()
}

const markAllRead = () => {
    notificationStore.markAllRead()
}

const logout = async () => {
    await authStore.logout()
}

const onSearch = () => {
    if (searchQuery.value.trim()) {
        // Emit search event or navigate to search page
        window.dispatchEvent(new CustomEvent('global-search', { detail: searchQuery.value }))
        searchQuery.value = ''
    }
}

const getNotificationIcon = (type) => {
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle',
        alert: 'fas fa-bell'
    }
    return icons[type] || 'fas fa-bell'
}

const formatTime = (date) => {
    const diff = new Date() - new Date(date)
    const minutes = Math.floor(diff / 60000)
    const hours = Math.floor(diff / 3600000)
    const days = Math.floor(diff / 86400000)
    
    if (minutes < 1) return 'Vừa xong'
    if (minutes < 60) return `${minutes} phút trước`
    if (hours < 24) return `${hours} giờ trước`
    return `${days} ngày trước`
}

// Close dropdowns when clicking outside
const handleClickOutside = (e) => {
    if (!e.target.closest('.dropdown')) {
        showLanguageDropdown.value = false
        showNotificationDropdown.value = false
        showUserDropdown.value = false
    }
}

// Lifecycle
onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.app-header {
    position: fixed;
    top: 0;
    right: 0;
    left: 280px;
    height: 60px;
    background: var(--bg-primary);
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    z-index: 99;
    transition: left 0.3s ease;
}

.app-header.header-collapsed {
    left: 70px;
}

/* Khi sidebar ẩn */
.sidebar-hidden ~ .app-header {
    left: 0;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-center {
    flex: 1;
    text-align: center;
}

.page-title {
    font-size: 1.25rem;
    margin: 0;
    font-weight: 500;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    color: var(--text-secondary);
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s;
    position: relative;
}

.header-btn:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.search-bar {
    display: flex;
    align-items: center;
    background: var(--bg-secondary);
    border-radius: 20px;
    padding: 6px 12px;
}

.search-bar i {
    color: var(--text-secondary);
    margin-right: 8px;
}

.search-bar input {
    border: none;
    background: none;
    outline: none;
    padding: 4px;
    width: 200px;
    color: var(--text-primary);
}

.logo {
    margin-left: 10px;
}

.logo-img {
    width: 32px;
    height: 32px;
}

.lang-code {
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 4px;
}

.user-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 20px;
    transition: background 0.2s;
}

.user-btn:hover {
    background: var(--bg-secondary);
}

.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    font-size: 0.9rem;
    font-weight: 500;
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: var(--danger);
    color: white;
    font-size: 10px;
    border-radius: 10px;
    padding: 2px 6px;
    min-width: 16px;
}

.dropdown {
    position: relative;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: var(--card-bg);
    border-radius: 8px;
    box-shadow: var(--shadow-lg);
    min-width: 250px;
    z-index: 100;
    margin-top: 8px;
    overflow: hidden;
}

.dropdown-menu-right {
    right: 0;
    left: auto;
}

.dropdown-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    font-weight: 600;
}

.dropdown-footer {
    padding: 8px 16px;
    border-top: 1px solid var(--border-color);
    text-align: center;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    color: var(--text-primary);
    transition: background 0.2s;
}

.dropdown-item:hover {
    background: var(--bg-secondary);
    text-decoration: none;
}

.dropdown-item img {
    width: 20px;
    height: 20px;
}

.dropdown-divider {
    height: 1px;
    background: var(--border-color);
    margin: 4px 0;
}

.text-danger {
    color: var(--danger);
}

/* Responsive */
@media (max-width: 768px) {
    .app-header {
        left: 0 !important;
    }
    
    .user-name {
        display: none;
    }
    
    .search-bar {
        display: none;
    }
    
    .page-title {
        font-size: 1rem;
    }
}
</style>