/**
 * Security Assessment Platform - Main Application
 * Xử lý các chức năng chính của ứng dụng
 */

import './bootstrap';

import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap';
import Swal from 'sweetalert2';
import moment from 'moment';

// ==================== GLOBAL VARIABLES ====================
window.bootstrap = bootstrap;
window.Swal = Swal;
window.moment = moment;

// ==================== ALPINE.JS ====================
window.Alpine = Alpine;
Alpine.start();

// ==================== SIDEBAR TOGGLE ====================
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on button click
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
        
        // Load saved state
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            sidebar.classList.add('collapsed');
        }
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// ==================== DATA TABLE INITIALIZATION ====================
document.querySelectorAll('.data-table').forEach(table => {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $(table).DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            },
            pageLength: 25,
            responsive: true
        });
    }
});

// ==================== DATE PICKER INITIALIZATION ====================
document.querySelectorAll('.datepicker').forEach(picker => {
    if (typeof flatpickr !== 'undefined') {
        flatpickr(picker, {
            dateFormat: 'Y-m-d',
            locale: 'vi'
        });
    }
});

// ==================== CONFIRM DELETE ====================
window.confirmDelete = function(message, callback) {
    Swal.fire({
        title: 'Xác nhận xóa?',
        text: message || 'Bạn có chắc chắn muốn xóa mục này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
};

// ==================== TOAST NOTIFICATION ====================
window.showToast = function(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    
    Toast.fire({
        icon: type,
        title: message
    });
};

// ==================== LOADING SPINNER ====================
window.showLoading = function() {
    Swal.fire({
        title: 'Đang xử lý...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

window.hideLoading = function() {
    Swal.close();
};

// ==================== FORMAT FUNCTIONS ====================
window.formatDate = function(date, format = 'DD/MM/YYYY HH:mm') {
    if (!date) return 'N/A';
    return moment(date).format(format);
};

window.formatBytes = function(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

window.truncate = function(str, length = 50) {
    if (!str) return '';
    if (str.length <= length) return str;
    return str.substring(0, length) + '...';
};

// ==================== FORM VALIDATION ====================
window.validateForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
        
        // Email validation
        if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                input.classList.add('is-invalid');
                isValid = false;
            }
        }
    });
    
    return isValid;
};

// ==================== AJAX REQUEST ====================
window.ajaxRequest = async function(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    };
    
    if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(url, options);
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Request failed');
        }
        
        return result;
    } catch (error) {
        console.error('AJAX Error:', error);
        showToast(error.message, 'error');
        throw error;
    }
};

// ==================== EXPORT FUNCTIONS ====================
window.exportToExcel = function(data, filename = 'export') {
    // Implementation can use SheetJS or similar library
    console.log('Export to Excel:', data);
    showToast('Đang xuất file...', 'info');
};

window.exportToPDF = function(elementId, filename = 'report') {
    // Implementation can use html2pdf or similar library
    console.log('Export to PDF:', elementId);
    showToast('Đang xuất PDF...', 'info');
};

// ==================== CHART HELPERS ====================
window.getChartColors = function() {
    return {
        primary: '#667eea',
        success: '#10b981',
        danger: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6',
        dark: '#1f2937'
    };
};

// ==================== THEME TOGGLE ====================
window.toggleTheme = function() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    showToast(`Đã chuyển sang chế độ ${newTheme === 'dark' ? 'tối' : 'sáng'}`, 'success');
};

// Load saved theme
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    document.documentElement.setAttribute('data-theme', savedTheme);
}

// ==================== NOTIFICATION POLLING ====================
let notificationInterval = null;

window.startNotificationPolling = function() {
    if (notificationInterval) clearInterval(notificationInterval);
    
    notificationInterval = setInterval(async () => {
        try {
            const response = await fetch('/api/notifications/unread-count');
            const data = await response.json();
            
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Notification polling error:', error);
        }
    }, 30000); // Poll every 30 seconds
};

window.stopNotificationPolling = function() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
        notificationInterval = null;
    }
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Start notification polling if user is logged in
    if (document.querySelector('.notification-badge')) {
        startNotificationPolling();
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});