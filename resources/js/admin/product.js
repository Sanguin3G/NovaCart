import {api} from '../utils/api.js';
import {Toast} from '../utils/toast.js';

// Product form handler
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('product-form');
    if (!form) return;
    const submitBtn = form.querySelector('button[type="submit"]');
    let loading = false;

    // Image URL preview
    const urlInput = document.getElementById('image_url');
    const previewImg = document.getElementById('image-preview');
    const modalImg = document.getElementById('modal-image');
    if (urlInput && previewImg) {
        urlInput.addEventListener('input', () => {
            const url = urlInput.value;
            if (url) {
                previewImg.src = url;
                previewImg.style.display = 'block';
                if (modalImg) modalImg.src = url;
            } else {
                previewImg.style.display = 'none';
            }
        });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (loading) return;
        loading = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.setAttribute('data-loading', '');
        }

        // Gather form data
        const name = form.querySelector('input[name="name"]').value;
        const description = form.querySelector('textarea[name="description"]').value;
        const price = form.querySelector('input[name="price"]').value;
        const stock = form.querySelector('input[name="stock"]').value;
        const category_id = form.querySelector('select[name="category_id"]').value;
        const is_active = form.querySelector('input[name="is_active"]').checked ? 1 : 0;
        const imageUrl = form.querySelector('input[name="image_url"]').value;

        const fd = new FormData();
        fd.append('name', name);
        fd.append('description', description);
        fd.append('price', price);
        fd.append('stock', stock);
        fd.append('category_id', category_id);
        fd.append('is_active', is_active);
        fd.append('image_url', imageUrl);

        // Determine if this is an update and spoof PUT via POST so Laravel parses FormData
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput && methodInput.value.toLowerCase() === 'put') {
            fd.append('_method', 'PUT');
        }
        try {
            const response = await api.post(form.action, fd);
            Toast.success(response.message || 'Product saved successfully.');

            // On create, redirect to index
            if (!methodInput) {
                window.location = form.action;
            }
        } catch (err) {
            Toast.error(err.message || 'Failed to save product.');
        } finally {
            loading = false;
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.removeAttribute('data-loading');
            }
        }
    });
});
