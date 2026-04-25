/**
 * VALIDATORS - Validation functions
 */

// Required validator
export const required = (value) => {
    if (value === null || value === undefined) return false
    if (typeof value === 'string') return value.trim().length > 0
    if (Array.isArray(value)) return value.length > 0
    return true
}

// Email validator
export const email = (value) => {
    if (!value) return true
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return re.test(value)
}

// Phone validator (Vietnam)
export const phone = (value) => {
    if (!value) return true
    const re = /^(0[3|5|7|8|9])+([0-9]{8})$/
    return re.test(value)
}

// Min length validator
export const minLength = (value, min) => {
    if (!value) return true
    return String(value).length >= min
}

// Max length validator
export const maxLength = (value, max) => {
    if (!value) return true
    return String(value).length <= max
}

// Range length validator
export const rangeLength = (value, min, max) => {
    if (!value) return true
    const len = String(value).length
    return len >= min && len <= max
}

// Min value validator
export const minValue = (value, min) => {
    if (value === null || value === undefined) return true
    return Number(value) >= min
}

// Max value validator
export const maxValue = (value, max) => {
    if (value === null || value === undefined) return true
    return Number(value) <= max
}

// Range value validator
export const rangeValue = (value, min, max) => {
    if (value === null || value === undefined) return true
    const num = Number(value)
    return num >= min && num <= max
}

// URL validator
export const url = (value) => {
    if (!value) return true
    try {
        new URL(value)
        return true
    } catch {
        return false
    }
}

// IP address validator
export const ip = (value) => {
    if (!value) return true
    const re = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/
    return re.test(value)
}

// Port validator
export const port = (value) => {
    if (!value) return true
    const num = Number(value)
    return num >= 1 && num <= 65535
}

// Password strength validator
export const passwordStrength = (value) => {
    if (!value) return false
    let score = 0
    if (value.length >= 8) score++
    if (value.length >= 12) score++
    if (/[A-Z]/.test(value)) score++
    if (/[a-z]/.test(value)) score++
    if (/[0-9]/.test(value)) score++
    if (/[^A-Za-z0-9]/.test(value)) score++
    return score >= 4
}

// Confirm password validator
export const confirmPassword = (value, confirmValue) => {
    return value === confirmValue
}

// Username validator
export const username = (value) => {
    if (!value) return false
    const re = /^[a-zA-Z0-9_]{3,20}$/
    return re.test(value)
}

// Alpha validator
export const alpha = (value, allowSpaces = false) => {
    if (!value) return true
    const re = allowSpaces ? /^[a-zA-Z\s]+$/ : /^[a-zA-Z]+$/
    return re.test(value)
}

// Alphanumeric validator
export const alphanumeric = (value, allowSpaces = false) => {
    if (!value) return true
    const re = allowSpaces ? /^[a-zA-Z0-9\s]+$/ : /^[a-zA-Z0-9]+$/
    return re.test(value)
}

// Numeric validator
export const numeric = (value) => {
    if (!value) return true
    return /^\d+$/.test(value)
}

// Decimal validator
export const decimal = (value) => {
    if (!value) return true
    return /^\d+(\.\d+)?$/.test(value)
}

// Date validator
export const date = (value) => {
    if (!value) return true
    const date = new Date(value)
    return !isNaN(date.getTime())
}

// Date range validator
export const dateRange = (startDate, endDate) => {
    if (!startDate || !endDate) return true
    return new Date(startDate) <= new Date(endDate)
}

// File type validator
export const fileType = (file, allowedTypes) => {
    if (!file) return true
    return allowedTypes.includes(file.type)
}

// File size validator
export const fileSize = (file, maxSize) => {
    if (!file) return true
    return file.size <= maxSize
}

// File extension validator
export const fileExtension = (file, allowedExtensions) => {
    if (!file) return true
    const extension = file.name.split('.').pop().toLowerCase()
    return allowedExtensions.includes(extension)
}

// Image dimensions validator
export const imageDimensions = (file, maxWidth, maxHeight) => {
    return new Promise((resolve) => {
        if (!file) {
            resolve(true)
            return
        }
        const img = new Image()
        img.onload = () => {
            const isValid = img.width <= maxWidth && img.height <= maxHeight
            resolve(isValid)
        }
        img.onerror = () => resolve(false)
        img.src = URL.createObjectURL(file)
    })
}

// Validation result helper
export const validate = (value, rules) => {
    const errors = []
    for (const rule of rules) {
        const { validator, message, params = [] } = rule
        let isValid = true
        if (typeof validator === 'function') {
            isValid = validator(value, ...params)
        }
        if (!isValid) {
            errors.push(message)
        }
    }
    return errors
}