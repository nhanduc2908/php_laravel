/**
 * THEME STORE - Quản lý giao diện sáng/tối
 */

import { defineStore } from 'pinia'

export const useThemeStore = defineStore('theme', {
    state: () => ({
        isDark: localStorage.getItem('theme') === 'dark' || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches && !localStorage.getItem('theme')),
    }),
    
    getters: {
        themeClass: (state) => state.isDark ? 'dark-theme' : 'light-theme',
        themeIcon: (state) => state.isDark ? 'fas fa-sun' : 'fas fa-moon',
    },
    
    actions: {
        toggleTheme() {
            this.isDark = !this.isDark
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light')
            this.applyTheme()
        },
        
        setTheme(dark) {
            this.isDark = dark
            localStorage.setItem('theme', dark ? 'dark' : 'light')
            this.applyTheme()
        },
        
        applyTheme() {
            if (this.isDark) {
                document.documentElement.classList.add('dark-theme')
                document.documentElement.classList.remove('light-theme')
            } else {
                document.documentElement.classList.add('light-theme')
                document.documentElement.classList.remove('dark-theme')
            }
        },
        
        loadTheme() {
            this.applyTheme()
        },
    },
})