<x-layouts.app :title="__('Checkout')">
    <div id="checkout-view" class="nc-page">
        <header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Shop') }}</p><h1 class="nc-title">{{ __('Checkout') }}</h1><p class="nc-subtitle">{{ __('Enter your delivery details and review the order before placing it.') }}</p></div></header>
            <div class="nc-card">
                <div class="md:grid md:grid-cols-10 md:gap-x-12 p-6 sm:p-8">
                    <!-- Checkout Form -->
                    <div class="md:col-span-6">
                        <form id="checkout-form" class="space-y-10">
                            @csrf
                            <!-- Contact Info -->
                            <section>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-5 border-b pb-3">Contact</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="sm:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                        <input type="text" name="name" id="name" required class="nc-control" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                        <input type="email" name="email" id="email" required class="nc-control" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}">
                                    </div>
                                </div>
                            </section>

                            <!-- Shipping -->
                            <section>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-5 border-b pb-3">Shipping</h3>
                                <label for="shipping_address" class="block text-sm font-medium">Address</label>
                                <textarea name="shipping_address" id="shipping_address" rows="4" required class="nc-control min-h-28 py-3"></textarea>
                            </section>
                            
                            <!-- Payment -->
                            <section>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-5 border-b pb-3">Payment</h3>
                                <select name="payment_method" id="payment_method" class="nc-select">
                                    <option value="credit_card">Credit Card (Mock)</option>
                                    <option value="paypal">PayPal (Mock)</option>
                                    <option value="bank_transfer">Bank Transfer (Mock)</option>
                                </select>
                            </section>
                            
                            <div id="form-errors" class="text-red-500 text-sm"></div>

                            <div class="mt-10 pt-6 border-t flex justify-end">
                                <button type="submit" id="submit-checkout-btn" class="nc-btn-primary px-8">
                                    Complete Checkout
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Order Summary -->
                    <div class="md:col-span-4 mt-12 md:mt-0">
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-5 border-b pb-3">Order Summary</h3>
                            <table id="order-summary-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium">Item</th>
                                        <th class="px-4 py-2 text-center font-medium">Qty</th>
                                        <th class="px-4 py-2 text-right font-medium">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="order-summary-items" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Rows inserted by JS -->
                                </tbody>
                            </table>
                            <div class="mt-6 pt-4 border-t">
                                <div class="flex justify-between items-center font-semibold">
                                    <span>Total</span>
                                    <span id="order-summary-total">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/customer/checkout.js')
    @endpush
</x-layouts.app>
