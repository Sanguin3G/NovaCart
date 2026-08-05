const STORAGE_KEY = 'theme';

function resolvedTheme(value) {
    return value === 'system'
        ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
        : value;
}

export function applyTheme(value = localStorage.getItem(STORAGE_KEY) || 'system') {
    const preference = ['light', 'dark', 'system'].includes(value) ? value : 'system';
    document.documentElement.classList.toggle('dark', resolvedTheme(preference) === 'dark');
    document.documentElement.dataset.theme = preference;
    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        button.setAttribute('aria-pressed', String(button.dataset.themeOption === preference));
    });
}

window.setAppearance = (value) => {
    localStorage.setItem(STORAGE_KEY, value);
    applyTheme(value);
};

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-theme-option]');
    if (button) window.setAppearance(button.dataset.themeOption);
});

document.addEventListener('DOMContentLoaded', () => {
    applyTheme();
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if ((localStorage.getItem(STORAGE_KEY) || 'system') === 'system') applyTheme('system');
    });
});
