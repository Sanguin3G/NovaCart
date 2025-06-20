import { Toast } from '../utils/toast.js';
import { api } from '../utils/api.js';
import { updateCartIcon } from '../utils/uiHelpers.js';
import { setButtonLoading } from '../utils/buttonLoading.js';

document.addEventListener('DOMContentLoaded', () => {
    const image = document.getElementById('main-product-image');
    const imageModal = document.getElementById('image-modal');
    const imageModalClose = document.getElementById('image-modal-close');
    const qtyInput = document.getElementById('quantity-input');
    const decrementBtn = document.querySelector('.qty-decrement');
    const incrementBtn = document.querySelector('.qty-increment');
    const productName = document.querySelector('h2')?.textContent.trim() || 'Item';

    if (image && imageModal) {
        image.addEventListener('click', () => {
            imageModal.classList.remove('hidden');
            imageModal.classList.add('flex');
        });

        imageModalClose?.addEventListener('click', () => {
            hideModal();
        });

        imageModal.addEventListener('click', (e) => {
            if (e.target === imageModal) hideModal();
        });

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') hideModal();
        });

        function hideModal() {
            imageModal.classList.add('hidden');
            imageModal.classList.remove('flex');
        }
    }

    if (qtyInput && decrementBtn && incrementBtn) {
        decrementBtn.addEventListener('click', () => {
            const current = parseInt(qtyInput.value, 10) || 1;
            if (current > 1) qtyInput.value = current - 1;
        });
        incrementBtn.addEventListener('click', () => {
            const current = parseInt(qtyInput.value, 10) || 1;
            qtyInput.value = current + 1;
        });
    }

    // Add-to-cart form AJAX
    const addToCartForm = document.querySelector('form[action*="cart/add"]');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = addToCartForm.querySelector('button[type="submit"]');
            const stock = parseInt(addToCartForm.dataset.stock,10);
            if(stock===0){
                Toast.info('This product is out of stock');
                return;
            }
            setButtonLoading(submitBtn, true);
            const formData = new FormData(addToCartForm);
            const quantity = formData.get('quantity') || 1;
            const action = addToCartForm.getAttribute('action');
            try {
                const result = await api.post(action, { quantity });
                const qty = parseInt(quantity);
                const msg = qty > 1 ? `Added ${qty} × ${productName} to cart` : (result.message || `"${productName}" added to cart`);
                Toast.success(msg);
                if (typeof result.cartItemCount !== 'undefined') {
                    updateCartIcon(result.cartItemCount);
                }
            } catch (err) {
                console.error(err);
                if (err.status === 401) {
                    Toast.info('Please log in to add items to your cart');
                } else {
                    Toast.error('Error adding to cart');
                }
            } finally {
                setButtonLoading(submitBtn, false);
            }
        });
    }
}); 