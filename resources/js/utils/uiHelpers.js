export function updateCartIcon(count) {
    const badges = document.querySelectorAll('#cart-item-count');
    if (!badges.length) return;

    const num = typeof count === 'string' ? parseInt(count, 10) || 0 : Number(count);

    badges.forEach(badge => {
        badge.textContent = String(num);

        if (num === 0) {
            badge.classList.remove('bg-orange-600', 'text-white');
            badge.classList.add('bg-gray-400', 'text-gray-100');
        } else {
            badge.classList.remove('bg-gray-400', 'text-gray-100');
            badge.classList.add('bg-orange-600', 'text-white');
        }
    });
}

export function updateSortLinks(links, currentSortBy, currentSortDirection) {
    links.forEach(link => {
        link.classList.remove('active', 'text-orange-600', 'dark:text-orange-400', 'font-semibold');
        const arrow = link.querySelector('.sort-arrow');
        if (arrow) arrow.remove();

        if (link.dataset.sortBy === currentSortBy) {
            link.classList.add('active', 'text-orange-600', 'dark:text-orange-400', 'font-semibold');
            const arrowEl = document.createElement('span');
            arrowEl.classList.add('sort-arrow', 'ml-1');
            arrowEl.innerHTML = currentSortDirection === 'asc' ? '▲' : '▼';
            link.appendChild(arrowEl);
        }
    });
}
