/**
 * HELPERS - Helper functions
 */

import { STORAGE_KEYS } from './constants'

// Debounce function
export const debounce = (func, wait) => {
    let timeout
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout)
            func(...args)
        }
        clearTimeout(timeout)
        timeout = setTimeout(later, wait)
    }
}

// Throttle function
export const throttle = (func, limit) => {
    let inThrottle
    return function(...args) {
        if (!inThrottle) {
            func(...args)
            inThrottle = true
            setTimeout(() => (inThrottle = false), limit)
        }
    }
}

// Deep clone object
export const deepClone = (obj) => {
    return JSON.parse(JSON.stringify(obj))
}

// Check if object is empty
export const isEmpty = (obj) => {
    return obj === null || obj === undefined || (typeof obj === 'object' && Object.keys(obj).length === 0)
}

// Generate random ID
export const generateId = (length = 8) => {
    return Math.random().toString(36).substring(2, 2 + length)
}

// Copy to clipboard
export const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text)
        return true
    } catch (err) {
        console.error('Copy failed:', err)
        return false
    }
}

// Download file from blob
export const downloadBlob = (blob, filename) => {
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = filename
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
}

// Download file from URL
export const downloadUrl = (url, filename) => {
    const link = document.createElement('a')
    link.href = url
    link.download = filename
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
}

// Get query params from URL
export const getQueryParams = () => {
    const params = new URLSearchParams(window.location.search)
    const result = {}
    for (const [key, value] of params) {
        result[key] = value
    }
    return result
}

// Build URL with query params
export const buildUrl = (base, params) => {
    const url = new URL(base, window.location.origin)
    Object.keys(params).forEach(key => {
        if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
            url.searchParams.append(key, params[key])
        }
    })
    return url.toString()
}

// Parse JSON safely
export const safeJsonParse = (json, defaultValue = null) => {
    try {
        return JSON.parse(json)
    } catch {
        return defaultValue
    }
}

// Check if value is a valid email
export const isValidEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return re.test(email)
}

// Check if value is a valid phone number (Vietnam)
export const isValidPhone = (phone) => {
    const re = /^(0[3|5|7|8|9])+([0-9]{8})$/
    return re.test(phone)
}

// Slugify string
export const slugify = (str) => {
    return str
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[đĐ]/g, 'd')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
}

// Capitalize first letter
export const capitalize = (str) => {
    if (!str) return ''
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
}

// Truncate string
export const truncate = (str, length = 50, suffix = '...') => {
    if (!str) return ''
    if (str.length <= length) return str
    return str.substring(0, length) + suffix
}

// Get random color
export const getRandomColor = () => {
    const letters = '0123456789ABCDEF'
    let color = '#'
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)]
    }
    return color
}

// Get color by string (hash-based)
export const getColorFromString = (str) => {
    let hash = 0
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash)
    }
    let color = '#'
    for (let i = 0; i < 3; i++) {
        const value = (hash >> (i * 8)) & 0xFF
        color += ('00' + value.toString(16)).substr(-2)
    }
    return color
}

// Get initial from name
export const getInitials = (name) => {
    if (!name) return '?'
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
}

// Check if running in browser
export const isBrowser = typeof window !== 'undefined'

// Check if running in development
export const isDev = import.meta.env.DEV

// Check if running in production
export const isProd = import.meta.env.PROD

// Get environment variable
export const env = (key, defaultValue = null) => {
    return import.meta.env[key] || defaultValue
}