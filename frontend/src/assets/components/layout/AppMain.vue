<template>
    <main class="app-main" :class="mainClass">
        <div class="main-container">
            <!-- Breadcrumb -->
            <div class="breadcrumb" v-if="showBreadcrumb">
                <router-link to="/">
                    <i class="fas fa-home"></i> Trang chủ
                </router-link>
                <template v-for="(crumb, index) in breadcrumbs" :key="index">
                    <i class="fas fa-chevron-right"></i>
                    <router-link :to="crumb.path" v-if="crumb.path">{{ crumb.title }}</router-link>
                    <span v-else>{{ crumb.title }}</span>
                </template>
            </div>
            
            <!-- Page Content -->
            <div class="page-content">
                <slot></slot>
            </div>
            
            <!-- Back to Top Button -->
            <button class="back-to-top" v-show="showBackToTop" @click="scrollToTop" title="Lên đầu trang">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </main>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useSidebarStore } from '@/stores/sidebar'

const route = useRoute()
const sidebarStore = useSidebarStore()

const showBackToTop = ref(false)
const showBreadcrumb = computed(() => route.meta?.breadcrumb !== false)

const breadcrumbs = computed(() => {
    const crumbs = []
    const paths = route.path.split('/').filter(p => p)
    let currentPath = ''
    
    for (const path of paths) {
        currentPath += '/' + path
        const matched = route.matched.find(r => r.path === currentPath)
        if (matched) {
            crumbs.push({
                title: matched.meta?.title || path,
                path: currentPath
            })
        }
    }
    
    return crumbs
})

const mainClass = computed(() => ({
    'sidebar-collapsed': sidebarStore.isCollapsed,
    'sidebar-hidden': sidebarStore.isHidden
}))

const handleScroll = () => {
    showBackToTop.value = window.scrollY > 300
}

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
.app-main {
    position: relative;
    min-height: calc(100vh - 60px);
    margin-top: 60px;
    margin-left: 280px;
    transition: margin-left 0.3s ease;
    background: var(--bg-primary);
}

.app-main.sidebar-collapsed {
    margin-left: 70px;
}

.app-main.sidebar-hidden {
    margin-left: 0;
}

.main-container {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 0.875rem;
    color: var(--text-secondary);
    flex-wrap: wrap;
}

.breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb a:hover {
    color: var(--primary-600);
}

.breadcrumb i {
    font-size: 0.7rem;
}

.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-600);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-md);
    transition: all 0.2s;
    z-index: 90;
}

.back-to-top:hover {
    background: var(--primary-700);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .app-main {
        margin-left: 0 !important;
    }
    
    .main-container {
        padding: 16px;
    }
    
    .back-to-top {
        bottom: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
    }
}
</style>