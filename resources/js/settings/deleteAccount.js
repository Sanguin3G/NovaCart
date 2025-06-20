import { api } from '../utils/api';
import { Toast } from '../utils/toast';
import setButtonLoading from '../utils/buttonLoading';

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('confirm_user_deletion');
    if (!modal) return;
    const form = modal.querySelector('form');
    if (!form) return;
    const submitBtn = form.querySelector('button[type="submit"]');
    const passwordInput = form.querySelector('input[name="password"]');
    // Create or select error container
    let errorDiv = form.querySelector('#password_error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'password_error';
        errorDiv.className = 'text-sm font-medium text-red-600 mt-1';
        passwordInput.insertAdjacentElement('afterend', errorDiv);
    }

    let loading = false;
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (loading) return;
        loading = true;
        // Clear previous error
        errorDiv.innerText = '';
        setButtonLoading(submitBtn, true);
        const formData = new FormData(form);
        try {
            const response = await api.post(form.action, formData);
            // On success, redirect to home
            if (response.redirect) {
                window.location = response.redirect;
            }
        } catch (err) {
            if (err.errors && err.errors.password) {
                errorDiv.innerText = err.errors.password[0];
                // Accessibility: mark input as invalid and link to error message
                passwordInput.setAttribute('aria-invalid', 'true');
                passwordInput.setAttribute('aria-describedby', errorDiv.id);
            } else {
                Toast.error(err.message || 'Failed to delete account.');
            }
        } finally {
            loading = false;
            setButtonLoading(submitBtn, false);
            passwordInput.value = '';
        }
    });
}); 