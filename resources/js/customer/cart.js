import {showToast} from '../utils/toast.js';
import {api} from '../utils/api.js';
import { updateCartIcon } from '../utils/uiHelpers.js';

document.addEventListener('DOMContentLoaded', function () {
    const cartView = document.getElementById('cart-view');
    if (!cartView) return;

    const itemsContainer = document.getElementById('cart-items-container');
    const cartSummary = document.getElementById('cart-summary');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const loadingIndicator = document.getElementById('cart-loading');
    const cartTotalEl = document.getElementById('cart-total');

    async function fetchCart(skipHide=false) {
        showLoading(true, skipHide);
        try {
            const data = await api.get('/cart');
            renderCart(data);
        } catch (error) {
            console.error('Error fetching cart:', error);
            itemsContainer.innerHTML = `<p class="text-red-500 text-center">Could not load your cart.</p>`;
        } finally {
            showLoading(false, skipHide);
        }
    }

    function renderCart(data) {
        itemsContainer.innerHTML = '';
        const cart = Object.values(data.cart);

        if (cart.length === 0) {
            cartSummary.style.display = 'none';
            emptyCartMessage.style.display = 'block';
        } else {
            cartSummary.style.display = 'block';
            emptyCartMessage.style.display = 'none';

            cart.forEach(item => {
                const itemHtml = `
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700" data-id="${item.id}">
                        <div class="flex items-center gap-4">
                            <img src="${item.image || 'https://via.placeholder.com/150'}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">${item.name}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">$${parseFloat(item.price).toFixed(2)}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden">
                                <button type="button" class="quantity-change px-2 py-1" data-change="-1" data-id="${item.id}">-</button>
                                <input type="text" value="${item.quantity}" class="w-10 text-center bg-transparent border-0" readonly>
                                <button type="button" class="quantity-change px-2 py-1" data-change="1" data-id="${item.id}">+</button>
                            </div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200 w-24 text-right">$${(item.price * item.quantity).toFixed(2)}</p>
                            <button type="button" class="remove-item cart-remove bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center" data-id="${item.id}">&times;</button>
                        </div>
                    </div>
                `;
                itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
            });

            cartTotalEl.textContent = `$${parseFloat(data.total).toFixed(2)}`;
        }
    }

    async function updateCart(productId, quantity) {
        try {
            const url = quantity > 0 ? `/cart/update/${productId}` : `/cart/remove/${productId}`;
            const data = await api.post(url, {quantity});
            if (quantity > 0) {
                const msg = `Updated quantity to ${quantity}`;
                showToast(msg);
            } else {
                showToast(data.message);
            }
            fetchCart(true); // Silent update without hiding list
            updateCartIcon(data.cartItemCount);

        } catch (error) {
            console.error('Error updating cart:', error);
            showToast(error.message, 'error');
        }
    }

    itemsContainer.addEventListener('click', e => {
        const quantityBtn = e.target.closest('.quantity-change');
        const removeBtn = e.target.closest('.remove-item');

        if (quantityBtn) {
            const itemDiv = quantityBtn.closest('[data-id]');
            if (!itemDiv) return;
            const productId = itemDiv.dataset.id;
            const quantityInput = quantityBtn.parentElement?.querySelector('input');
            if (!quantityInput) return;
            const change = parseInt(quantityBtn.dataset.change);
            const newQuantity = parseInt(quantityInput.value) + change;
            if (newQuantity > 0) {
                updateCart(productId, newQuantity);
            }
        }

        if (removeBtn) {
            import('../utils/toast.js').then(({Confirm}) => {
                Confirm.delete({text: 'This item will be removed from your cart.'}).then(result => {
                    if (result.isConfirmed) {
                        const row = removeBtn.closest('[data-id]');
                        row.classList.add('cart-row-removing');
                        setTimeout(()=>updateCart(removeBtn.dataset.id,0),350);
                    }
                });
            });
        }
    });

    function showLoading(isLoading, skipHide=false) {
        loadingIndicator.style.display = isLoading ? 'block' : 'none';
        if(!skipHide){
            itemsContainer.style.display = isLoading ? 'none' : 'block';
        }
        if (!isLoading) {
            // Also ensure summary/empty message are correctly displayed after loading
            const hasItems = itemsContainer.children.length > 0;
            cartSummary.style.display = hasItems ? 'block' : 'none';
            emptyCartMessage.style.display = hasItems ? 'none' : 'block';
        }
    }

    fetchCart();
});
