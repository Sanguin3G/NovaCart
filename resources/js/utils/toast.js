function container() {
    let element = document.getElementById('toast-container');
    if (!element) {
        element = document.createElement('div');
        element.id = 'toast-container';
        element.className = 'fixed right-4 top-4 z-[100] flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3';
        document.body.append(element);
    }
    return element;
}

function show(message, type = 'success') {
    const item = document.createElement('div');
    const colors = {
        success: 'border-green-200 bg-green-50 text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200',
        error: 'border-red-200 bg-red-50 text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200',
        info: 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-200',
        warning: 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200',
    };
    item.className = 'rounded-lg border px-4 py-3 text-sm shadow-lg ' + (colors[type] || colors.info);
    item.textContent = message;
    container().append(item);
    window.setTimeout(() => item.remove(), type === 'error' ? 5000 : 3000);
}

export const Toast = {
    success: (message) => show(message, 'success'),
    error: (message) => show(message, 'error'),
    info: (message) => show(message, 'info'),
    warning: (message) => show(message, 'warning'),
};

export const Confirm = {
    confirm: (options = {}) => Promise.resolve({
        isConfirmed: window.confirm([options.title || 'Are you sure?', options.text || ''].filter(Boolean).join('\n\n')),
    }),
    delete: (options = {}) => Confirm.confirm({
        title: options.title || 'Delete item',
        text: options.text || 'This action cannot be undone.',
    }),
};

export function debounce(fn, delay) {
    let timeoutId;
    return function (...args) {
        window.clearTimeout(timeoutId);
        timeoutId = window.setTimeout(() => fn.apply(this, args), delay);
    };
}

export function showToast(message, type = 'success') {
    (Toast[type] || Toast.success)(message);
}
