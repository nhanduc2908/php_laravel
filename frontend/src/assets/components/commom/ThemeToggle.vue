<template>
    <button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Chuyển sang chế độ sáng' : 'Chuyển sang chế độ tối'">
        <i :class="isDark ? 'fas fa-sun' : 'fas fa-moon'"></i>
        <span class="theme-label">{{ isDark ? 'Sáng' : 'Tối' }}</span>
    </button>
</template>

<script setup>
import { computed } from 'vue'
import { useThemeStore } from '@/stores/theme'

const themeStore = useThemeStore()
const isDark = computed(() => themeStore.isDark)
const toggleTheme = () => themeStore.toggleTheme()
</script>

<style scoped>
.theme-toggle { display: flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 20px; cursor: pointer; transition: all 0.2s; color: var(--text-primary); }
.theme-toggle:hover { background: var(--primary-600); color: white; }
.theme-label { font-size: 0.875rem; }
</style>