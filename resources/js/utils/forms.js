export function fillSelect(selectEl, items, clearExisting = false) {
    if (!selectEl || !Array.isArray(items)) return;
    if (clearExisting) {
        selectEl.innerHTML = '';
    }
    items.forEach(({ id, name }) => {
        const option = new Option(name, id);
        selectEl.add(option);
    });
}

// Utility: run an async job with loading spinner on a submit button
import { setButtonLoading } from './buttonLoading.js';
import { Toast } from './toast.js';
import { api } from './api.js';

export async function withSubmitLoading(buttonEl, job) {
    if (!buttonEl || typeof job !== 'function') return;
    setButtonLoading(buttonEl, true);
    try {
        return await job();
    } finally {
        setButtonLoading(buttonEl, false);
    }
}

export function clearValidationErrors(formEl) {
    if (!formEl) return;
    formEl.querySelectorAll('[data-error]').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
}

export function showValidationErrors(formEl, errors) {
    if (!formEl || !errors) return;
    Object.entries(errors).forEach(([field, messages]) => {
        const target = formEl.querySelector(`[data-error="${field}"]`);
        if (target) {
            target.textContent = messages[0];
            target.classList.remove('hidden');
        }
    });
}

// Generic AJAX form handler
export function ajaxForm(formEl, { onSuccess } = {}) {
    if (!formEl) return;
    const submitBtn = formEl.querySelector('[type="submit"]');

    formEl.addEventListener('submit', async e => {
        e.preventDefault();

        await withSubmitLoading(submitBtn, async () => {
            clearValidationErrors(formEl);
            const formData = new FormData(formEl);

            let response;
            try {
                response = await api.post(formEl.action, formData);
            } catch (err) {
                if (err.status === 422 && err.errors) {
                    showValidationErrors(formEl, err.errors);
                    return;
                }
                Toast.error(err.message || 'An error occurred');
                return;
            }

            Toast.success(response.message || 'Saved successfully');
            if (typeof onSuccess === 'function') onSuccess(response);
        });
    });
} 