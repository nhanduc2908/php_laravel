/**
 * SECURITY ASSESSMENT PLATFORM - FRONTEND ENTRY POINT
 * App entry, mount Vue, configure plugins
 */

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import App from './App.vue'
import router from './router'
import i18n from './plugins/i18n'
import socket from './plugins/socket'

// Import global styles
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import './assets/css/main.css'
import './assets/css/responsive.css'

// Import Toast
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

// Import Loading directive
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/css/index.css'

// Import Vue3 Select
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

// Create Pinia instance
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

// Create app
const app = createApp(App)

// Register global components
app.component('v-select', vSelect)

// Use plugins
app.use(pinia)
app.use(router)
app.use(i18n)
app.use(socket)
app.use(Toast, {
    position: 'top-right',
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: false
})
app.use(Loading, {
    color: '#667eea',
    loader: 'spinner',
    width: 64,
    height: 64,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    opacity: 0.8,
    zIndex: 9999
})

// Global error handler
app.config.errorHandler = (err, vm, info) => {
    console.error('Global error:', err)
    console.error('Error info:', info)
    
    // Show toast notification for errors
    const toast = app.config.globalProperties.$toast
    if (toast) {
        toast.error(err.message || 'An unexpected error occurred')
    }
}

// Global property for API URL
app.config.globalProperties.$apiUrl = import.meta.env.VITE_API_URL

// Mount app
app.mount('#app')

// Service Worker registration for PWA
if ('serviceWorker' in navigator && import.meta.env.PROD) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').then(registration => {
            console.log('SW registered: ', registration)
        }).catch(registrationError => {
            console.log('SW registration failed: ', registrationError)
        })
    })
}