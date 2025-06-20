import Swal from 'sweetalert2';

// Configure default toast settings
const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    showClass: {popup: 'swal2-toast-show', backdrop: 'swal2-backdrop-show'},
    hideClass: {popup: 'swal2-toast-hide', backdrop: 'swal2-backdrop-hide'},
    didOpen: (toastEl) => {
        toastEl.addEventListener('mouseenter', Swal.stopTimer);
        toastEl.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Utility to detect current app theme
function getToastTheme() {
    const appearance = window.localStorage.getItem('appearance');
    if (appearance === 'dark') return 'dark';
    if (appearance === 'light') return 'light';
    // fallback to auto for system preference
    return 'auto';
}

// Toast notifications
export const Toast = {
    success: (message) => {
        toast.fire({
            icon: 'success',
            title: message,
            iconColor: 'var(--swal2-success)',
            background: 'var(--swal2-background)',
            color: 'var(--swal2-html-container-color)',
            theme: getToastTheme(),
        });
    },
    error: (message) => {
        toast.fire({
            icon: 'error',
            title: message,
            timer: 5000, // Longer for errors
            iconColor: 'var(--swal2-error)',
            background: 'var(--swal2-background)',
            color: 'var(--swal2-html-container-color)',
            theme: getToastTheme(),
        });
    },
    info: (message) => {
        toast.fire({
            icon: 'info',
            title: message,
            iconColor: 'var(--swal2-info)',
            background: 'var(--swal2-background)',
            color: 'var(--swal2-html-container-color)',
            theme: getToastTheme(),
        });
    },
    warning: (message) => {
        toast.fire({
            icon: 'warning',
            title: message,
            iconColor: 'var(--swal2-warning)',
            background: 'var(--swal2-background)',
            color: 'var(--swal2-html-container-color)',
            theme: getToastTheme(),
        });
    }
};

// Confirm dialogs
export const Confirm = {
    confirm: (options = {}) => {
        return Swal.fire({
            title: options.title || 'Are you sure?',
            text: options.text || '',
            icon: options.icon || 'question',
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Yes',
            cancelButtonText: options.cancelText || 'Cancel',
            confirmButtonColor: 'var(--swal2-confirm)',
            cancelButtonColor: 'var(--swal2-cancel)',
            background: 'var(--swal2-background)',
            color: 'var(--swal2-html-container-color)',
            theme: getToastTheme(),
            reverseButtons: true,
            showClass: {
                popup: 'swal2-show',
                backdrop: 'swal2-backdrop-show',
                icon: 'swal2-icon-show'
            },
            hideClass: {
                popup: 'swal2-hide',
                backdrop: 'swal2-backdrop-hide',
                icon: 'swal2-icon-hide'
            },
            ...options.customOptions
        });
    },
    delete: (options = {}) => {
        return Confirm.confirm({
            title: options.title || 'Delete item',
            text: options.text || 'This action cannot be undone',
            confirmText: options.confirmText || 'Delete',
            cancelText: options.cancelText || 'Cancel',
            icon: 'warning',
            customOptions: {
                confirmButtonColor: '#ef4444',
                ...options.customOptions
            }
        });
    }
};

/**
 * Creates a debounced function that delays invoking the provided function.
 * @param {Function} fn The function to debounce.
 * @param {number} delay The number of milliseconds to delay.
 * @returns {Function} The new debounced function.
 */
export function debounce(fn, delay) {
    let timeoutId;
    return function(...args) {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {
            fn.apply(this, args);
        }, delay);
    };
}

export function showToast(message, type = 'success') {
    if (type === 'error' || type === 'danger') {
        Toast.error(message);
    } else if (type === 'info') {
        Toast.info(message);
    } else if (type === 'warning') {
        Toast.warning(message);
    } else {
        Toast.success(message);
    }
}
