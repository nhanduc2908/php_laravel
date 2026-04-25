/**
 * useApi - Composable for API calls with loading and error handling
 */

import { ref } from 'vue'
import { useToast } from 'vue-toastification'

export function useApi() {
    const loading = ref(false)
    const error = ref(null)
    const toast = useToast()
    
    const execute = async (apiCall, showSuccessToast = false, successMessage = 'Thành công') => {
        loading.value = true
        error.value = null
        
        try {
            const result = await apiCall()
            if (showSuccessToast) {
                toast.success(successMessage)
            }
            return { success: true, data: result }
        } catch (err) {
            error.value = err
            const message = err.response?.data?.message || err.message || 'Đã xảy ra lỗi'
            toast.error(message)
            return { success: false, error: err, message }
        } finally {
            loading.value = false
        }
    }
    
    const executeWithLoading = async (apiCall, options = {}) => {
        const { showToast = true, successMessage = 'Thành công', errorMessage } = options
        loading.value = true
        
        try {
            const result = await apiCall()
            if (showToast) toast.success(successMessage)
            return { success: true, data: result }
        } catch (err) {
            const message = errorMessage || err.response?.data?.message || err.message || 'Đã xảy ra lỗi'
            if (showToast) toast.error(message)
            return { success: false, error: err, message }
        } finally {
            loading.value = false
        }
    }
    
    const get = async (url, params = {}) => {
        return execute(() => fetch(url, { params }).then(r => r.json()))
    }
    
    const post = async (url, data = {}) => {
        return execute(() => fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        }).then(r => r.json()))
    }
    
    const put = async (url, data = {}) => {
        return execute(() => fetch(url, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        }).then(r => r.json()))
    }
    
    const del = async (url) => {
        return execute(() => fetch(url, { method: 'DELETE' }).then(r => r.json()))
    }
    
    return {
        loading,
        error,
        execute,
        executeWithLoading,
        get,
        post,
        put,
        delete: del,
    }
}