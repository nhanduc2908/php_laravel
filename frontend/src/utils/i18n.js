/**
 * I18N - Internationalization utilities
 */

import { ref } from 'vue'

// Language store
const currentLocale = ref(localStorage.getItem('locale') || 'vi')
const translations = ref({})

// Language definitions
export const languages = [
    { code: 'en', name: 'English', nativeName: 'English', flag: '🇺🇸' },
    { code: 'vi', name: 'Vietnamese', nativeName: 'Tiếng Việt', flag: '🇻🇳' },
    { code: 'ja', name: 'Japanese', nativeName: '日本語', flag: '🇯🇵' },
]

// Load translations from API
export const loadTranslations = async (locale) => {
    try {
        const response = await fetch(`/api/v1/translations/${locale}`)
        const data = await response.json()
        if (data.status === 'success') {
            translations.value = data.data || {}
            return true
        }
    } catch (error) {
        console.error('Failed to load translations:', error)
    }
    return false
}

// Translate function
export const t = (key, params = {}) => {
    let text = translations.value[key] || key
    Object.keys(params).forEach(param => {
        text = text.replace(`:${param}`, params[param])
    })
    return text
}

// Set locale
export const setLocale = async (locale) => {
    if (!languages.find(l => l.code === locale)) {
        locale = 'vi'
    }
    currentLocale.value = locale
    localStorage.setItem('locale', locale)
    document.documentElement.lang = locale
    await loadTranslations(locale)
    window.dispatchEvent(new CustomEvent('language-changed', { detail: { locale } }))
}

// Get current locale
export const getLocale = () => currentLocale.value

// Get current language object
export const getCurrentLanguage = () => {
    return languages.find(l => l.code === currentLocale.value)
}

// Initialize i18n
export const initI18n = async () => {
    await loadTranslations(currentLocale.value)
    document.documentElement.lang = currentLocale.value
}

// Translation keys
export const i18nKeys = {
    // Common
    welcome: 'welcome',
    login: 'login',
    logout: 'logout',
    register: 'register',
    dashboard: 'dashboard',
    settings: 'settings',
    profile: 'profile',
    save: 'save',
    cancel: 'cancel',
    delete: 'delete',
    edit: 'edit',
    view: 'view',
    search: 'search',
    filter: 'filter',
    reset: 'reset',
    confirm: 'confirm',
    loading: 'loading',
    success: 'success',
    error: 'error',
    warning: 'warning',
    info: 'info',
    
    // Navigation
    users: 'users',
    roles: 'roles',
    servers: 'servers',
    criteria: 'criteria',
    assessments: 'assessments',
    files: 'files',
    reports: 'reports',
    alerts: 'alerts',
    vulnerabilities: 'vulnerabilities',
    audit: 'audit',
    backup: 'backup',
    
    // Actions
    create: 'create',
    update: 'update',
    view_details: 'view_details',
    confirm_delete: 'confirm_delete',
    are_you_sure: 'are_you_sure',
    yes_delete: 'yes_delete',
    yes_confirm: 'yes_confirm',
    
    // Validation
    field_required: 'field_required',
    invalid_email: 'invalid_email',
    invalid_phone: 'invalid_phone',
    password_too_short: 'password_too_short',
    password_not_match: 'password_not_match',
    
    // Time
    just_now: 'just_now',
    minutes_ago: 'minutes_ago',
    hours_ago: 'hours_ago',
    days_ago: 'days_ago',
}