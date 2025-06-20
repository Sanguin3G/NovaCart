<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div id="cart-view" class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Your Cart</h3>

                <!-- Loading Spinner -->
                <div id="cart-loading" style="display: none;" class="text-center py-10">
                    <p>Loading cart...</p>
                </div>

                <!-- Cart Items Container -->
                <div id="cart-items-container" class="space-y-4">
                    {{-- Cart items will be injected here by JavaScript --}}
                </div>

                <!-- Cart Totals and Actions -->
                <div id="cart-summary" style="display: none;">
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Order Total:</h4>
                            <p id="cart-total" class="text-2xl font-bold text-orange-600 dark:text-orange-400">$0.00</p>
                        </div>
                        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <a href="{{ route('products.index') }}" class="w-full sm:w-auto order-last sm:order-first inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Continue Shopping
                            </a>
                            <a href="{{ route('checkout.show') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart Message -->
                <div id="empty-cart-message" style="display: none;" class="text-center py-10">
                    <p class="text-gray-600 dark:text-gray-400 text-lg">Your cart is empty.</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                        Start Shopping
                    </a>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/customer/cart.js')
    @endpush
</x-layouts.app>