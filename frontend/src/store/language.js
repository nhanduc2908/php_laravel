/**
 * LANGUAGE STORE - Quản lý đa ngôn ngữ
 */

import { defineStore } from 'pinia'

export const useLanguageStore = defineStore('language', {
    state: () => ({
        currentLocale: localStorage.getItem('locale') || 'vi',
        supportedLanguages: [
            { code: 'en', name: 'English', nativeName: 'English', flag: '/assets/images/flags/en.svg' },
            { code: 'vi', name: 'Vietnamese', nativeName: 'Tiếng Việt', flag: '/assets/images/flags/vi.svg' },
            { code: 'ja', name: 'Japanese', nativeName: '日本語', flag: '/assets/images/flags/ja.svg' },
        ],
        translations: {},
    }),
    
    getters: {
        currentLanguage: (state) => state.supportedLanguages.find(l => l.code === state.currentLocale),
        t: (state) => (key, params = {}) => {
            let text = state.translations[key] || key
            Object.keys(params).forEach(param => {
                text = text.replace(`:${param}`, params[param])
            })
            return text
        },
    },
    
    actions: {
        async setLocale(locale) {
            if (!this.supportedLanguages.find(l => l.code === locale)) {
                locale = 'vi'
            }
            
            this.currentLocale = locale
            localStorage.setItem('locale', locale)
            document.documentElement.lang = locale
            
            await this.loadTranslations(locale)
            
            // Emit event for language change
            window.dispatchEvent(new CustomEvent('language-changed', { detail: { locale } }))
        },
        
        async loadTranslations(locale) {
            try {
                const response = await fetch(`/api/v1/translations/${locale}`)
                const data = await response.json()
                if (data.status === 'success') {
                    this.translations = data.data || {}
                }
            } catch (error) {
                console.error('Load translations failed:', error)
                // Fallback to empty translations
                this.translations = {}
            }
        },
        
        init() {
            this.loadTranslations(this.currentLocale)
        },
    },
})