import 'instant.page';
import './actions/popover';
import './actions/delete';
import './actions/statusToggle';
import './actions/loading';
import './actions/modal';
import './actions/sidebar';
import 'sweetalert2/dist/sweetalert2.min.css';
import {Confirm, Toast} from './utils/toast.js';
import {api, formWithMethod} from './utils/api';
import Chart from 'chart.js/auto';
import './settings/profile';
import './settings/password';
import './admin/category.js';
import './admin/product.js';
import './settings/deleteAccount';
import './customer/order-actions.js';
import './admin/order-actions.js';
import {initButtonLoading, setButtonLoading} from './utils/buttonLoading';
import {updateCartIcon} from './utils/uiHelpers.js';

// Make utilities available globally
window.Toast = Toast;
window.Confirm = Confirm;
window.api = api;
window.Chart = Chart;
window.formWithMethod = formWithMethod;
window.setButtonLoading = setButtonLoading;
window.initButtonLoading = initButtonLoading;

// Global error handler
window.addEventListener('error', (event) => {
    console.error('Global error:', event.error);
    Toast.error('An unexpected error occurred');
});

// Handle unhandled promise rejections
window.addEventListener('unhandledrejection', (event) => {
    event.preventDefault();
    const message = event.reason?.response?.data?.message ||
        event.reason?.message ||
        'An unexpected error occurred';
    console.error('Unhandled rejection:', event.reason);
    Toast.error(message);
});

// Ensure cart badge color/visibility reflects initial markup value
document.addEventListener('DOMContentLoaded', () => {
    const badge = document.getElementById('cart-item-count');
    if (badge) {
        const initial = parseInt(badge.textContent, 10) || 0;
        updateCartIcon(initial);
    }
});
