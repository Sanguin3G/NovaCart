<x-layouts.app :title="__('Order Details')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details') }} - #{{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold leading-tight text-gray-900 dark:text-white">
                        Order #{{ $order->order_number }}
                    </h3>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="p-6">
                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('Placed on') }}: {{ $order->created_at->format('F j, Y, g:i a') }}
                    </p>
                    <!-- Order Items -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Items Ordered') }}:</h4>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Product') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Qty') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Price') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Subtotal') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                            <div class="flex items-center">
                                                @if($item->product && $item->product->image_url)
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-10 h-10 object-cover rounded mr-3">
                                                @else
                                                    <img src="https://via.placeholder.com/40" alt="Placeholder" class="w-10 h-10 object-cover rounded mr-3">
                                                @endif
                                                <span>{{ $item->product ? $item->product->name : 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">${{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Total') }}:</td>
                                    <td class="px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Addresses -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Shipping Address') }}:</h4>
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $order->shipping_address }}</div>
                        </div>
                        @if($order->billing_address)
                            <div>
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Billing Address') }}:</h4>
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $order->billing_address }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Payment & Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('Payment Method') }}:</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-200">{{ $order->payment_method ?? 'N/A' }}</p>
                        </div>
                        @if($order->notes)
                            <div class="md:col-span-2">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('Notes') }}:</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div id="order-actions-container" data-order-id="{{ $order->id }}" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 flex items-center gap-3">
                        @switch($order->status)
                            @case('pending')
                                <button data-action="processing" class="action-btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                                    {{ __('Mark as Processing') }}
                                </button>
                                <button data-action="cancelled" class="action-btn bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                                    {{ __('Cancel Order') }}
                                </button>
                                @break

                            @case('processing')
                                <button data-action="shipped" class="action-btn bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg">
                                    {{ __('Mark as Shipped') }}
                                </button>
                                <button data-action="cancelled" class="action-btn bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                                    {{ __('Cancel Order') }}
                                </button>
                                @break

                            @case('shipped')
                                <button data-action="completed" class="action-btn bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                                    {{ __('Mark as Completed') }}
                                </button>
                                @break

                            @default
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    This order is in a final state and no further actions can be taken.
                                </p>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

@push('scripts')
    @vite('resources/js/admin/order-show.js')
@endpush
