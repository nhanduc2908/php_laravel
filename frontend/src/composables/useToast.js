/**
 * useToast - Composable for toast notifications
 */

import { useToast as useVueToast } from 'vue-toastification'

export function useToast() {
    const toast = useVueToast()
    
    const success = (message, options = {}) => {
        toast.success(message, {
            timeout: 3000,
            ...options,
        })
    }
    
    const error = (message, options = {}) => {
        toast.error(message, {
            timeout: 5000,
            ...options,
        })
    }
    
    const warning = (message, options = {}) => {
        toast.warning(message, {
            timeout: 4000,
            ...options,
        })
    }
    
    const info = (message, options = {}) => {
        toast.info(message, {
            timeout: 3000,
            ...options,
        })
    }
    
    const promise = async (promise, messages) => {
        return toast.promise(promise, {
            pending: messages.pending || 'Đang xử lý...',
            success: messages.success || 'Thành công',
            error: messages.error || 'Thất bại',
        })
    }
    
    return {
        success,
        error,
        warning,
        info,
        promise,
    }
}