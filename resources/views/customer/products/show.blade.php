<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div id="product-show" class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden">
                <!-- Product Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold leading-tight text-gray-900 dark:text-white">
                        {{ $product->name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Category: {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                </div>

                <!-- Product Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Image (Clickable for Modal) -->
                        <div class="md:col-span-1">
                            <img id="main-product-image"
                                 src="{{ $product->image ?? 'https://via.placeholder.com/400' }}"
                                 alt="{{ $product->name }}"
                                 class="rounded-lg object-cover w-full aspect-square shadow border border-gray-200 dark:border-gray-700 cursor-pointer hover:opacity-90 transition-opacity">
                        </div>
                        <!-- Details -->
                        <div class="md:col-span-2 space-y-4 flex flex-col"> {{-- Added flex flex-col --}}
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h4>
                                <p class="text-base text-gray-800 dark:text-gray-200 mt-1 leading-relaxed">{{ $product->description }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</h4>
                                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                                        ${{ number_format($product->price, 2) }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock</h4>
                                    <p class="text-lg font-semibold mt-1 {{ $product->stock_quantity == 0 ? 'text-gray-400' : ($product->stock_quantity <=5 ? 'text-red-500 animate-pulse' : 'text-green-600') }}">
                                        {{ $product->stock_quantity == 0 ? 'Out of stock' : ($product->stock_quantity <=5 ? $product->stock_quantity.' left!' : $product->stock_quantity.' in stock') }}
                                    </p>
                                </div>
                            </div>
                            {{-- Added mt-auto to push button down --}}
                            <div class="pt-4 mt-auto">
                                <form action="{{ route('cart.add', $product) }}" method="POST" data-stock="{{ $product->stock_quantity }}">
                                    @csrf
                                    <div class="flex items-center gap-4">
                                        {{-- Quantity Input Group --}}
                                        <div
                                            class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden shadow-sm">
                                            <button type="button" class="qty-decrement quantity-change px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition-colors duration-150 ease-in-out">
                                                -
                                            </button>
                                            <input type="number" name="quantity" id="quantity-input" min="1" value="1"
                                                   class="w-16 text-center border-y border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-0 appearance-none [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:m-0 [&::-webkit-inner-spin-button]:m-0">
                                            <button type="button" class="qty-increment quantity-change px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition-colors duration-150 ease-in-out">
                                                +
                                            </button>
                                        </div>
                                        {{-- Add to Cart Button with Icon, Gradient, and Effects --}}
                                        <x-button type="submit" variant="primary"
                                                  class="flex-1 sm:flex-none px-6 py-2 font-semibold flex items-center gap-2 bg-gradient-to-r from-orange-500 via-pink-500 to-purple-500 text-white shadow-lg shadow-orange-500/30 transition-all duration-200 ease-in-out transform hover:scale-105 hover:from-pink-500 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-opacity-50 active:scale-95"
                                                  :disabled="$product->stock_quantity <= 0"
                                        >
                                            <x-icon name="heroicon-o-shopping-cart" class="h-5 w-5 inline-block"/>
                                            <span>Add to Cart</span>
                                        </x-button>
                                    </div>
                                </form>
                            </div>
                            <div class="pt-4">
                                @php $avg = round($product->averageRating(),1); @endphp
                                <div class="flex justify-end items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-1">{{ __('Avg:') }} <span id="avg-rating-text" class="text-orange-600 dark:text-orange-400">{{ $avg }}/5</span></span>
                                </div>

                                @if($canReview)
                                    <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Rate this product') }}</h4>
                                    <!-- Interactive widget -->
                                    <div id="star-widget" data-product-review-url="{{ route('products.reviews.store', $product) }}" class="mt-1 flex items-center space-x-1 cursor-pointer text-gray-300 dark:text-gray-600">
                                        @for($i=1;$i<=5;$i++)
                                            <svg data-value="{{ $i }}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 star" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.2 3.677a1 1 0 00.95.69h3.862c.969 0 1.371 1.24.588 1.81l-3.124 2.27a1 1 0 00-.364 1.118l1.2 3.678c.3.922-.755 1.688-1.54 1.118L10 13.348l-3.124 2.27c-.785.57-1.84-.196-1.54-1.118l1.2-3.678a1 1 0 00-.364-1.118L3.048 9.104c-.783-.57-.38-1.81.588-1.81h3.862a1 1 0 00.95-.69l1.2-3.677z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <button id="write-review-btn" class="mt-2 text-sm text-orange-600 hover:underline hidden" type="button">
                                        {{ __('Write a review') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Link -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('products.index') }}"
                       class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                        &larr; Back to Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="image-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75 p-4">

            <div class="relative bg-white dark:bg-gray-900 rounded-lg shadow-xl max-w-5xl max-h-[80vh] overflow-auto">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/800' }}"
                     alt="{{ $product->name }} - Large View" class="block w-full h-auto">
                <button id="image-modal-close"
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white bg-white/50 dark:bg-black/50 rounded-full p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Review Modal -->
        <x-modal id="review-modal" closable="true">
            <div class="bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 p-4 rounded-t-lg text-white">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <x-icon name="heroicon-o-star" class="h-6 w-6"/>
                    {{ __('Write a review') }}
                </h3>
            </div>
            <form id="review-form" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="rating" id="review-rating" value="0">
                <div>
                    <label for="review-body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Your review') }}</label>
                    <textarea id="review-body" name="body" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500"></textarea>
                </div>
                @guest
                <div>
                    <label for="review-name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Your name') }}</label>
                    <input type="text" id="review-name" name="reviewer_name" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500">
                </div>
                @endguest
                <div class="text-right">
                    <x-button type="submit" variant="primary">{{ __('Submit review') }}</x-button>
                </div>
            </form>
        </x-modal>

        <!-- Reviews List -->
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mt-10" id="product-reviews-wrapper" data-product-id="{{ $product->id }}">
            <h3 class="text-lg font-semibold mb-4 border-b-2 border-orange-500 pb-2">{{ __('Reviews') }}</h3>
            <div id="reviews-list" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 rounded-lg shadow"></div>
            <div id="reviews-pagination" class="mt-4 flex justify-end"></div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/customer/product-show.js')
        @vite('resources/js/customer/review-stars.js')
        @vite('resources/js/customer/product-reviews-list.js')
    @endpush
</x-layouts.app>
