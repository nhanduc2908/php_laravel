/**
 * Bootstrap - Load các thư viện và cấu hình cơ bản
 */

import _ from 'lodash';
window._ = _;

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

/**
 * Echo - Laravel WebSocket broadcasting
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

if (typeof window.io !== 'undefined') {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    });
} else if (typeof Pusher !== 'undefined') {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true
    });
}

/**
 * Alpine.js
 */
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/**
 * Flatpickr - Date picker
 */
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'flatpickr/dist/themes/material_blue.css';
window.flatpickr = flatpickr;

/**
 * Select2 - Enhanced select boxes
 */
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

if (typeof $.fn.select2 !== 'undefined') {
    $(document).ready(function() {
        $('select:not(.no-select2)').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
}

/**
 * DataTables
 */
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
window.DataTable = DataTable;

/**
 * Chart.js
 */
import Chart from 'chart.js/auto';
window.Chart = Chart;

/**
 * Moment.js
 */
import moment from 'moment';
import 'moment/locale/vi';
moment.locale('vi');
window.moment = moment;

/**
 * SweetAlert2
 */
import Swal from 'sweetalert2';
window.Swal = Swal;

/**
 * Loading overlay
 */
window.showPageLoader = function() {
    let loader = document.querySelector('.page-loader');
    if (!loader) {
        loader = document.createElement('div');
        loader.className = 'page-loader';
        loader.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
        document.body.appendChild(loader);
    }
    loader.style.display = 'flex';
};

window.hidePageLoader = function() {
    const loader = document.querySelector('.page-loader');
    if (loader) {
        loader.style.display = 'none';
    }
};

// Add loader styles
const style = document.createElement('style');
style.textContent = `
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    [data-theme="dark"] {
        --bg-primary: #1a1a2e;
        --text-primary: #eee;
        --border-color: #333;
    }
`;
document.head.appendChild(style);

/**
 * Auto-refresh CSRF token
 */
setInterval(() => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    }
}, 300000); // Refresh every 5 minutes