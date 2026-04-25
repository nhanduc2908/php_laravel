/**
 * I18N PLUGIN - Vue Internationalization plugin
 */

import { createI18n } from 'vue-i18n'

// Import locale messages
import en from '@/locales/en.json'
import vi from '@/locales/vi.json'
import ja from '@/locales/ja.json'

// Get default locale from localStorage or browser
const getDefaultLocale = () => {
    const saved = localStorage.getItem('locale')
    if (saved && ['en', 'vi', 'ja'].includes(saved)) return saved
    
    const browserLang = navigator.language.split('-')[0]
    if (['en', 'vi', 'ja'].includes(browserLang)) return browserLang
    
    return 'vi' // Default to Vietnamese
}

// Create i18n instance
export const i18n = createI18n({
    legacy: false,
    locale: getDefaultLocale(),
    fallbackLocale: 'en',
    messages: {
        en,
        vi,
        ja,
    },
    numberFormats: {
        en: {
            currency: { style: 'currency', currency: 'USD' },
            decimal: { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 },
            percent: { style: 'percent', minimumFractionDigits: 1, maximumFractionDigits: 1 },
        },
        vi: {
            currency: { style: 'currency', currency: 'VND' },
            decimal: { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 },
            percent: { style: 'percent', minimumFractionDigits: 1, maximumFractionDigits: 1 },
        },
        ja: {
            currency: { style: 'currency', currency: 'JPY' },
            decimal: { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 },
            percent: { style: 'percent', minimumFractionDigits: 1, maximumFractionDigits: 1 },
        },
    },
    datetimeFormats: {
        en: {
            short: { year: 'numeric', month: 'short', day: 'numeric' },
            long: { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' },
            datetime: { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' },
            time: { hour: 'numeric', minute: 'numeric', second: 'numeric' },
        },
        vi: {
            short: { year: 'numeric', month: 'numeric', day: 'numeric' },
            long: { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' },
            datetime: { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' },
            time: { hour: 'numeric', minute: 'numeric', second: 'numeric' },
        },
        ja: {
            short: { year: 'numeric', month: 'numeric', day: 'numeric' },
            long: { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' },
            datetime: { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' },
            time: { hour: 'numeric', minute: 'numeric', second: 'numeric' },
        },
    },
})

// Watch for language changes
let currentLocale = i18n.global.locale.value

const handleLanguageChange = (event) => {
    const newLocale = event.detail?.locale
    if (newLocale && newLocale !== currentLocale) {
        currentLocale = newLocale
        i18n.global.locale.value = newLocale
        localStorage.setItem('locale', newLocale)
        document.documentElement.lang = newLocale
    }
}

// Listen for custom language change event
if (typeof window !== 'undefined') {
    window.addEventListener('language-changed', handleLanguageChange)
}

// Helper functions
export const setLanguage = (locale) => {
    if (i18n.global.availableLocales.includes(locale)) {
        i18n.global.locale.value = locale
        localStorage.setItem('locale', locale)
        document.documentElement.lang = locale
        return true
    }
    return false
}

export const getCurrentLanguage = () => i18n.global.locale.value

export const getAvailableLanguages = () => i18n.global.availableLocales

export const t = (key, params = {}) => i18n.global.t(key, params)

export const n = (number, format = 'decimal') => i18n.global.n(number, format)

export const d = (date, format = 'datetime') => i18n.global.d(date, format)

// Export plugin
export default {
    install(app) {
        app.use(i18n)
        
        // Provide global methods
        app.config.globalProperties.$t = t
        app.config.globalProperties.$n = n
        app.config.globalProperties.$d = d
        app.config.globalProperties.$setLanguage = setLanguage
        app.config.globalProperties.$getLanguage = getCurrentLanguage
    },
}