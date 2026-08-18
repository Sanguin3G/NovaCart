import {debounce, Toast} from '../utils/toast.js';
import { setButtonLoading } from '../utils/buttonLoading.js';
import { api } from '../utils/api.js';
import { updateCartIcon, updateSortLinks } from '../utils/uiHelpers.js';
import { renderPagination } from '../utils/pagination.js';
import { toggleListLoading } from '../utils/listLoading.js';
import { fillSelect } from '../utils/forms.js';

document.addEventListener('DOMContentLoaded', function () {
    const productListView = document.getElementById('product-list-view');
    if (!productListView) return;

    const productGrid = document.getElementById('product-grid');
    const categoryFilter = document.getElementById('category-filter');
    const searchInput = document.getElementById('product-search');
    const sortLinks = document.querySelectorAll('.sort-link');
    const paginationContainer = document.getElementById('pagination-links');
    const loadingSpinner = document.getElementById('loading-spinner');

    let currentSearch = '';
    let currentCategory = '';
    let currentSortBy = 'name';
    let currentSortDirection = 'asc';
    let currentPage = 1;

    async function fetchProducts() {
        showLoading(true);
        const query = {
            search: currentSearch,
            category: currentCategory,
            sort_by: currentSortBy,
            direction: currentSortDirection,
            page: currentPage
        };

        try {
            const data = await api.get('/products', query);

            const productsArray = data.data;
            const paginationObj = data;
            renderProducts(productsArray);
            updateSortUI();
            if (paginationObj && paginationObj.meta) {
                renderPagination(paginationContainer, paginationObj.meta, async (page, el) => {
                    currentPage = page;
                    setButtonLoading(el, true);
                    await fetchProducts();
                    setButtonLoading(el, false);
                });
            } else {
                paginationContainer.innerHTML = '';
            }
            if (categoryFilter.options.length <= 1 && data.categories) {
                populateCategoryFilter(data.categories);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            productGrid.innerHTML = `<p class="text-center text-red-500 col-span-full">Failed to load products. Please try again later.</p>`;
        } finally {
            showLoading(false);
        }
    }

    function renderProducts(products) {
        productGrid.innerHTML = '';
        if (products.length === 0) {
            productGrid.innerHTML = `<p class="text-center text-gray-500 col-span-full">No products found.</p>`;
            return;
        }
        products.forEach(product => {
            const productCard = `
                <article class="nc-card overflow-hidden flex flex-col">
                    <a href="/products/${product.id}" class="block aspect-square overflow-hidden">
                        <img src="${product.image || 'https://via.placeholder.com/300x300'}" alt="${product.name}" class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                    </a>
                    <div class="flex flex-grow flex-col p-4">
                        <h2 class="mb-1 truncate text-base font-semibold text-gray-900 dark:text-white">
                            <a href="/products/${product.id}" class="hover:text-orange-600 dark:hover:text-orange-400" title="${product.name}">${product.name}</a>
                        </h2>
                        <p class="mb-1 text-lg font-bold text-orange-600 dark:text-orange-400">$${parseFloat(product.price).toFixed(2)}</p>
                        <p class="mb-3 text-xs ${product.stock_quantity === 0 ? 'text-gray-400' : (product.stock_quantity <=5 ? 'text-red-500 animate-pulse' : 'text-gray-500')} ">
                            ${product.stock_quantity === 0 ? 'Out of stock' : (product.stock_quantity <=5 ? product.stock_quantity + ' left!' : product.stock_quantity + ' in stock')}
                        </p>
                        <div class="mt-auto">
                           ${product.stock_quantity > 0 ?
                                `<form class="add-to-cart-form" data-product-id="${product.id}" data-stock="${product.stock_quantity}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="nc-btn-primary w-full">Add to cart</button>
                                </form>` :
                `<p class="text-xs text-red-500 dark:text-red-400 mt-1 text-center">Out of stock</p>`
            }
                        </div>
                    </div>
                </article>
            `;
            productGrid.insertAdjacentHTML('beforeend', productCard);
        });
    }

    function updateSortUI() {
        updateSortLinks(sortLinks, currentSortBy, currentSortDirection);
    }

    function populateCategoryFilter(categories) {
        fillSelect(categoryFilter, categories);
    }

    function showLoading(isLoading) {
        toggleListLoading(loadingSpinner, productGrid, isLoading);
    }

    // Event listeners
    searchInput.addEventListener('input', debounce(() => {
        currentPage = 1;
        currentSearch = searchInput.value;
        fetchProducts();
    }, 300));

    categoryFilter.addEventListener('change', () => {
        currentPage = 1;
        currentCategory = categoryFilter.value;
        fetchProducts();
    });

    sortLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const sortBy = link.dataset.sortBy;
            if (currentSortBy === sortBy) {
                currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortBy = sortBy;
                currentSortDirection = 'asc';
            }
            currentPage = 1;
            fetchProducts();
        });
    });

    // Add to cart form submission
    productGrid.addEventListener('submit', async e => {
        if (e.target.classList.contains('add-to-cart-form')) {
            e.preventDefault();
            const form = e.target;
            const productId = form.dataset.productId;
            const stock = parseInt(form.dataset.stock,10);
            if(stock === 0){
                Toast.info('This product is out of stock');
                return;
            }
            const quantity = 1;
            try {
                const result = await api.post(`/cart/add/${productId}`, { quantity });
                if (result) {
                    Toast.success(result.message || 'Added to cart');
                    if (typeof result.cartItemCount !== 'undefined') {
                        updateCartIcon(result.cartItemCount);
                    }
                }
            } catch (err) {
                console.error(err);
                if (err.status === 401) {
                    Toast.info('Please log in to add items to your cart');
                } else {
                    Toast.error(err.message || 'Error adding to cart');
                }
            }
        }
    });

    // initial load
    fetchProducts();
});
