import { ajaxForm } from '../utils/forms.js';

// Password form handler
document.addEventListener('DOMContentLoaded', () => {
    ajaxForm(document.getElementById('password-form'), {
        onSuccess: () => document.getElementById('password-form').reset()
    });
}); 