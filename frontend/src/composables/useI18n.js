/**
 * useI18n - Composable for internationalization
 */

import { computed } from 'vue'
import { useLanguageStore } from '@/stores/language'

export function useI18n() {
    const languageStore = useLanguageStore()
    
    const locale = computed(() => languageStore.currentLocale)
    const t = (key, params = {}) => languageStore.t(key, params)
    
    const setLocale = (newLocale) => {
        languageStore.setLocale(newLocale)
    }
    
    const formatDate = (date, format = 'short') => {
        if (!date) return ''
        const d = new Date(date)
        const formats = {
            short: () => d.toLocaleDateString(locale.value),
            long: () => d.toLocaleDateString(locale.value, { year: 'numeric', month: 'long', day: 'numeric' }),
            datetime: () => d.toLocaleString(locale.value),
            time: () => d.toLocaleTimeString(locale.value),
        }
        return formats[format]?.() || formats.short()
    }
    
    const formatNumber = (number, options = {}) => {
        return new Intl.NumberFormat(locale.value, options).format(number)
    }
    
    const formatCurrency = (amount, currency = 'VND') => {
        return new Intl.NumberFormat(locale.value, {
            style: 'currency',
            currency,
        }).format(amount)
    }
    
    const formatPercent = (value, decimals = 0) => {
        return new Intl.NumberFormat(locale.value, {
            style: 'percent',
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
        }).format(value / 100)
    }
    
    const formatFileSize = (bytes) => {
        if (bytes === 0) return '0 Bytes'
        const k = 1024
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
        const i = Math.floor(Math.log(bytes) / Math.log(k))
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
    }
    
    const formatRelativeTime = (date) => {
        const now = new Date()
        const diff = now - new Date(date)
        const seconds = Math.floor(diff / 1000)
        const minutes = Math.floor(seconds / 60)
        const hours = Math.floor(minutes / 60)
        const days = Math.floor(hours / 24)
        
        if (seconds < 60) return t('time.just_now')
        if (minutes < 60) return t('time.minutes_ago', { count: minutes })
        if (hours < 24) return t('time.hours_ago', { count: hours })
        if (days < 7) return t('time.days_ago', { count: days })
        return formatDate(date, 'short')
    }
    
    return {
        locale,
        t,
        setLocale,
        formatDate,
        formatNumber,
        formatCurrency,
        formatPercent,
        formatFileSize,
        formatRelativeTime,
    }
}