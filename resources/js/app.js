import htmx from 'htmx.org';
import './actions/popover';
import './actions/delete';
import './actions/statusToggle';
import './actions/loading';
import './actions/modal';
import './actions/sidebar';
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
import './admin/reviews.js';
import {initButtonLoading, setButtonLoading} from './utils/buttonLoading';
import {updateCartIcon} from './utils/uiHelpers.js';
import './theme.js';

window.htmx = htmx;
document.addEventListener('htmx:configRequest', (event) => {
    event.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;
    event.detail.headers.Accept = 'text/html';
});
document.addEventListener('htmx:responseError', (event) => {
    Toast.error('Could not load that section. Please try again.');
    const target = event.detail?.target;
    if (target) {
        target.innerHTML = `<div class="nc-state"><svg class="text-red-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 8v5M12 17h.01"/><path d="m10.3 3.8-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3.2l-8-14a2 2 0 0 0-3.4 0Z"/></svg><p class="nc-state-title">Could not load this section</p><p class="nc-state-copy">Check the request and try again.</p><button type="button" class="nc-btn-secondary js-htmx-retry">Try again</button></div>`;
        target.querySelector('.js-htmx-retry')?.addEventListener('click', () => htmx.trigger(target, 'load'), {once: true});
    }
});

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
    const message = event.reason?.data?.message ||
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
