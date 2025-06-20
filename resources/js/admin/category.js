import { ajaxForm } from '../utils/forms.js';

// Category form handler
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('category-form');
    if (!form) return;

    ajaxForm(form, {
        onSuccess: () => {
            // If this was a create request (no _method = PUT) then redirect back to index
            const methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                window.location.href = form.getAttribute('data-index-url') || window.location.href;
            }
        },
    });
});
