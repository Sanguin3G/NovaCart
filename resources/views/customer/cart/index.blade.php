<x-layouts.app :title="__('Shopping cart')">
    <div id="cart-view" class="nc-page">
        <header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Shop') }}</p><h1 class="nc-title">{{ __('Shopping cart') }}</h1><p class="nc-subtitle">{{ __('Review your items before moving on to checkout.') }}</p></div></header>
        <div class="nc-card p-6">

                <!-- Loading Spinner -->
                <div id="cart-loading" style="display: none;" class="nc-state">
                    <x-icon name="shopping-bag" width="22" height="22" class="text-orange-500" /><p>{{ __('Loading cart…') }}</p>
                </div>

                <!-- Cart Items Container -->
                <div id="cart-items-container" class="space-y-4">
                    {{-- Cart items will be injected here by JavaScript --}}
                </div>

                <!-- Cart Totals and Actions -->
                <div id="cart-summary" style="display: none;">
                    <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Order Total:</h4>
                            <p id="cart-total" class="text-2xl font-bold text-orange-600 dark:text-orange-400">$0.00</p>
                        </div>
                        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <a href="{{ route('products.index') }}" class="nc-btn-secondary w-full sm:order-first sm:w-auto">
                                Continue Shopping
                            </a>
                            <a href="{{ route('checkout.show') }}" class="nc-btn-primary w-full sm:w-auto">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart Message -->
                <div id="empty-cart-message" style="display: none;" class="nc-state">
                    <p class="text-gray-600 dark:text-gray-400 text-lg">{{ __('Your cart is empty.') }}</p>
                    <a href="{{ route('products.index') }}" class="nc-btn-primary">
                        Start Shopping
                    </a>
                </div>

        </div>
    </div>

    @push('scripts')
        @vite('resources/js/customer/cart.js')
    @endpush
</x-layouts.app>
