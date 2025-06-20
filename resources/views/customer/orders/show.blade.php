<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details') }} - #{{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Session Success Message --}}
            @if (session('success'))
                <div id="order-success-alert"
                     class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300 shadow-md"
                     role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                <!-- Order Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold leading-tight text-gray-900 dark:text-white">
                            Order #{{ $order->order_number }}
                        </h3>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @switch($order->status)
                                @case('pending') bg-yellow-200 text-yellow-800 @break
                                @case('processing') bg-blue-200 text-blue-800 @break
                                @case('completed') bg-green-200 text-green-800 @break
                                @case('shipped') bg-indigo-200 text-indigo-800 @break
                                @case('cancelled') bg-red-200 text-red-800 @break
                                @default bg-gray-200 text-gray-800
                            @endswitch">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Placed on: {{ $order->created_at->format('F j, Y, g:i a') }}
                    </p>
                </div>

                <!-- Order Body -->
                <div class="p-6 space-y-6">
                    <!-- Order Items -->
                    <div>
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Items Ordered:</h4>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                            <div class="flex items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ $item->product->image }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="w-10 h-10 object-cover rounded mr-3">
                                                @else
                                                    <img src="https://via.placeholder.com/40" alt="Placeholder"
                                                         class="w-10 h-10 object-cover rounded mr-3">
                                                @endif
                                                <span>{{ $item->product ? $item->product->name : 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                            ${{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                            ${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="3"
                                        class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Total:
                                    </td>
                                    <td class="px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100">
                                        ${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Shipping & Billing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Shipping
                                Address:</h4>
                            <div
                                class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $order->shipping_address }}</div>
                        </div>
                        @if($order->billing_address)
                            <div>
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Billing
                                    Address:</h4>
                                <div
                                    class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $order->billing_address }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Payment & Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-1">Payment Method:</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-200">{{ $order->payment_method ?? 'N/A' }}</p>
                        </div>
                        @if($order->notes)
                            <div class="md:col-span-2">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-1">Your Notes:</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer  -->
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center flex-col sm:flex-row gap-3 sm:gap-0">
                    <!-- Back link aligned left -->
                    <a href="{{ route('orders.index') }}"
                       class="text-sm font-medium text-orange-600 hover:text-orange-500 dark:text-orange-400 dark:hover:text-orange-300">
                        &larr; Back to My Orders
                    </a>

                    <!-- Cancel button on the right for pending orders -->
                    @if($order->status === 'pending')
                        <button id="cancel-order-btn" data-id="{{ $order->id }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Cancel Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/customer/order-show.js')
    @endpush
</x-layouts.app>
