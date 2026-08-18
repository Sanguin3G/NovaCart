<x-layouts.app :title="__('Browse products')">
    <div id="product-list-view" class="nc-page">
        <header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Shop') }}</p><h1 class="nc-title">{{ __('Browse products') }}</h1><p class="nc-subtitle">{{ __('Find practical, well-made goods for your everyday setup.') }}</p></div></header>
        <form id="filter-form" class="nc-toolbar"><div class="nc-toolbar-group"><div class="relative w-full"><x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="17" height="17" /><label for="product-search" class="sr-only">{{ __('Search products') }}</label><input type="search" name="search" id="product-search" placeholder="{{ __('Search products…') }}" class="nc-search"></div><div class="w-full sm:max-w-xs"><label for="category-filter" class="sr-only">{{ __('Filter by category') }}</label><select name="category" id="category-filter" class="nc-select"><option value="">{{ __('All categories') }}</option></select></div></div></form>
        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold text-gray-700 dark:text-gray-200">{{ __('Sort by') }}</span><a href="#" data-sort-by="name" data-sort-direction="asc" class="sort-link nc-btn-ghost h-9">{{ __('Name') }}</a><a href="#" data-sort-by="price" data-sort-direction="asc" class="sort-link nc-btn-ghost h-9">{{ __('Price') }}</a></div>
        <div id="session-messages"></div>
        <div id="loading-spinner" class="nc-state" style="display:none"><x-icon name="package" width="22" height="22" class="text-orange-500" /><span>{{ __('Loading products…') }}</span></div>
        <div id="product-grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"></div>
        <div id="pagination-links" class="flex justify-end"></div>
    </div>

    @push('scripts')
        @vite('resources/js/customer/products.js')
    @endpush
</x-layouts.app>
