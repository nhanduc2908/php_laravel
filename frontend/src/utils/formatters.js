/**
 * FORMATTERS - Formatting functions for dates, numbers, etc.
 */

import moment from 'moment'
import 'moment/locale/vi'

// Set locale for moment
moment.locale('vi')

// Date formatters
export const formatDate = (date, format = 'DD/MM/YYYY') => {
    if (!date) return ''
    return moment(date).format(format)
}

export const formatDateTime = (date, format = 'DD/MM/YYYY HH:mm:ss') => {
    if (!date) return ''
    return moment(date).format(format)
}

export const formatTime = (date, format = 'HH:mm:ss') => {
    if (!date) return ''
    return moment(date).format(format)
}

export const formatRelativeTime = (date) => {
    if (!date) return ''
    return moment(date).fromNow()
}

export const formatCalendar = (date) => {
    if (!date) return ''
    return moment(date).calendar()
}

// Number formatters
export const formatNumber = (number, decimals = 0, locale = 'vi-VN') => {
    if (number === null || number === undefined) return '0'
    return new Intl.NumberFormat(locale, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(number)
}

export const formatCurrency = (amount, currency = 'VND', locale = 'vi-VN') => {
    if (amount === null || amount === undefined) return '0 ₫'
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount)
}

export const formatPercent = (value, decimals = 1, locale = 'vi-VN') => {
    if (value === null || value === undefined) return '0%'
    return new Intl.NumberFormat(locale, {
        style: 'percent',
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(value / 100)
}

export const formatCompactNumber = (number, locale = 'vi-VN') => {
    if (number === null || number === undefined) return '0'
    return new Intl.NumberFormat(locale, {
        notation: 'compact',
        compactDisplay: 'short',
    }).format(number)
}

// File size formatter
export const formatFileSize = (bytes, decimals = 2) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(decimals)) + ' ' + sizes[i]
}

// Duration formatter
export const formatDuration = (seconds) => {
    if (!seconds) return '0s'
    const hours = Math.floor(seconds / 3600)
    const minutes = Math.floor((seconds % 3600) / 60)
    const secs = seconds % 60
    
    const parts = []
    if (hours > 0) parts.push(`${hours}h`)
    if (minutes > 0) parts.push(`${minutes}m`)
    if (secs > 0 || parts.length === 0) parts.push(`${secs}s`)
    return parts.join(' ')
}

// String formatters
export const capitalize = (str) => {
    if (!str) return ''
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
}

export const capitalizeWords = (str) => {
    if (!str) return ''
    return str.split(' ').map(word => capitalize(word)).join(' ')
}

export const truncate = (str, length = 50, suffix = '...') => {
    if (!str) return ''
    if (str.length <= length) return str
    return str.substring(0, length) + suffix
}

// Phone formatter (Vietnam)
export const formatPhone = (phone) => {
    if (!phone) return ''
    const cleaned = phone.replace(/\D/g, '')
    if (cleaned.length === 10) {
        return cleaned.replace(/(\d{4})(\d{3})(\d{3})/, '$1 $2 $3')
    }
    return phone
}

// ID card formatter (Vietnam)
export const formatIdCard = (id) => {
    if (!id) return ''
    const cleaned = id.replace(/\D/g, '')
    if (cleaned.length === 9 || cleaned.length === 12) {
        return cleaned.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3')
    }
    return id
}

// Severity formatters
export const formatSeverity = (severity) => {
    const labels = {
        critical: 'Nghiêm trọng',
        high: 'Cao',
        medium: 'Trung bình',
        low: 'Thấp',
        info: 'Thông tin',
    }
    return labels[severity] || severity
}

// Status formatters
export const formatStatus = (status) => {
    const labels = {
        active: 'Hoạt động',
        inactive: 'Không hoạt động',
        pending: 'Chờ xử lý',
        completed: 'Hoàn tất',
        failed: 'Thất bại',
        draft: 'Nháp',
        published: 'Đã xuất bản',
        archived: 'Lưu trữ',
        open: 'Chưa xử lý',
        fixed: 'Đã sửa',
        false_positive: 'False Positive',
    }
    return labels[status] || status
}

// Role formatter
export const formatRole = (role) => {
    const labels = {
        super_admin: 'Super Admin',
        admin: 'Admin',
        security_officer: 'Security Officer',
        viewer: 'Viewer',
        auditor: 'Auditor',
    }
    return labels[role] || role
}