<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div id="product-list-view" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header & Filters -->
            <div class="mb-6">
                <form id="filter-form" class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg shadow-sm border dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <!-- Search Input -->
                        <div class="flex-1 min-w-0 w-full">
                            <label for="product-search" class="sr-only">Search products</label>
                            <x-input
                                type="search"
                                name="search"
                                id="product-search"
                                placeholder="Search products..."
                                class="w-full"
                            />
                        </div>

                        <!-- Category Filter -->
                        <div class="w-full sm:w-56">
                            <label for="category-filter" class="sr-only">Filter by category</label>
                            <x-select name="category" id="category-filter" class="w-full">
                                <option value="">All Categories</option>
                                {{-- Categories will be populated by JS --}}
                            </x-select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sorting Links -->
            <div class="mb-4 text-sm flex items-center gap-4 text-gray-600 dark:text-gray-400">
                <span>Sort by:</span>
                <a href="#" data-sort-by="name" data-sort-direction="asc" class="sort-link hover:text-orange-600 dark:hover:text-orange-400">Name</a>
                <a href="#" data-sort-by="price" data-sort-direction="asc" class="sort-link hover:text-orange-600 dark:hover:text-orange-400">Price</a>
            </div>

            <!-- Session Messages Container -->
            <div id="session-messages"></div>

            <!-- Loading Spinner -->
            <div id="loading-spinner" class="text-center py-10" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <!-- Product Grid -->
            <div id="product-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                {{-- Product cards will be injected here by JavaScript --}}
            </div>

            <!-- Pagination -->
            <div id="pagination-links" class="mt-8">
                {{-- Pagination links will be injected here by JavaScript --}}
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/customer/products.js')
    @endpush
</x-layouts.app>
