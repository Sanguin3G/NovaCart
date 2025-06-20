import { renderPagination } from '../utils/pagination.js';
import { api } from '../utils/api.js';
import { Toast } from '../utils/toast.js';

document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('reviews-list');
    const pager = document.getElementById('reviews-pagination');
    if (!wrapper) return;
    const wrapperEl = document.getElementById('product-reviews-wrapper');
    const productId = wrapperEl ? wrapperEl.dataset.productId : null;
    if (!productId) return;

    const fetchPage = async (page = 1) => {
        try {
            const data = await api.get(`/products/${productId}/reviews/data`, { page });
            render(data);
        } catch (e) {
            Toast.error('Failed to load reviews');
        }
    };

    const render = (json) => {
        wrapper.innerHTML = json.data.map(r => {
            const stars = Array.from({length:5}).map((_,i)=> i<r.rating
                ? '<svg class="h-4 w-4 text-yellow-400 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.2 3.677a1 1 0 00.95.69h3.862c.969 0 1.371 1.24.588 1.81l-3.124 2.27a1 1 0 00-.364 1.118l1.2 3.678c.3.922-.755 1.688-1.54 1.118L10 13.348l-3.124 2.27c-.785.57-1.84-.196-1.54-1.118l1.2-3.678a1 1 0 00-.364-1.118L3.048 9.104c-.783-.57-.38-1.81.588-1.81h3.862a1 1 0 00.95-.69l1.2-3.677z" /></svg>'
                : '<svg class="h-4 w-4 text-gray-300 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.2 3.677a1 1 0 00.95.69h3.862c.969 0 1.371 1.24.588 1.81l-3.124 2.27a1 1 0 00-.364 1.118l1.2 3.678c.3.922-.755 1.688-1.54 1.118L10 13.348l-3.124 2.27c-.785.57-1.84-.196-1.54-1.118l1.2-3.678a1 1 0 00-.364-1.118L3.048 9.104c-.783-.57-.38-1.81.588-1.81h3.862a1 1 0 00.95-.69l1.2-3.677z" /></svg>').join('');

            return `<div class="p-4 review-card">
                        <p class="font-semibold text-gray-800 dark:text-gray-100">${r.reviewer_name}</p>
                        <div class="mt-1">${stars} <span class="text-xs text-gray-500">${r.rating}/5</span></div>
                        <p class="mt-2 text-gray-700 dark:text-gray-300 whitespace-pre-line">${r.body}</p>
                    </div>`;
        }).join('');
        renderPagination(pager, json, (page)=>fetchPage(page));
    };

    fetchPage();
}); 