import { api } from '../utils/api.js';
import { Toast } from '../utils/toast.js';
import { setButtonLoading } from '../utils/buttonLoading.js';

document.addEventListener('DOMContentLoaded', function () {
    const checkoutView = document.getElementById('checkout-view');
    if (!checkoutView) return;

    const form = document.getElementById('checkout-form');
    const summaryItemsContainer = document.getElementById('order-summary-items');
    const summaryTotalEl = document.getElementById('order-summary-total');
    const formErrorsContainer = document.getElementById('form-errors');
    const submitButton = document.getElementById('submit-checkout-btn');

    async function fetchCartSummary() {
        try {
            const data = await api.get('/cart');
            renderOrderSummary(data);
        } catch (error) {
            console.error('Error fetching cart summary:', error);
            summaryItemsContainer.innerHTML = `<p class="text-red-500 text-center text-sm">Could not load summary.</p>`;
        }
    }

    function renderOrderSummary(data) {
        summaryItemsContainer.innerHTML = '';
        const cart = Object.values(data.cart);

        if (cart.length === 0) {
            summaryItemsContainer.innerHTML = `<p class="text-gray-500 text-sm">Your cart is empty.</p>`;
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }

        cart.forEach(item => {
            const row = `
                <tr>
                    <td class="px-4 py-2 truncate">${item.name}</td>
                    <td class="px-4 py-2 text-center">${item.quantity}</td>
                    <td class="px-4 py-2 text-right">$${(item.price * item.quantity).toFixed(2)}</td>
                </tr>
            `;
            summaryItemsContainer.insertAdjacentHTML('beforeend', row);
        });

        summaryTotalEl.textContent = `$${parseFloat(data.total).toFixed(2)}`;
    }
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        setButtonLoading(submitButton, true);
        formErrorsContainer.innerHTML = '';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await api.post('/checkout', data).catch(err=>err);
            // When api throws error we catch above and treat as err object
            if (response instanceof Error) {
                if (response.status === 422 && response.data?.errors) {
                    displayErrors(response.data.errors);
                } else if (response.status === 401) {
                    window.location.href = '/login';
                } else {
                    Toast.error(response.message);
                }
                return;
            }

            // success
            Toast.success(response.message || 'Order placed successfully!');
            setTimeout(()=>{ window.location.href = response.redirect_url; }, 1200);

        } catch (error) {
            console.error('Checkout error:', error);
            Toast.error(error.message || 'Checkout failed');
        } finally {
            setButtonLoading(submitButton, false);
        }
    });

    function displayErrors(errors) {
        Toast.error(Object.values(errors)[0][0]);
    }

    // Initial load
    fetchCartSummary();
});
