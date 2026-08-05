<div class="overflow-x-auto"><table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700"><thead><tr><th>{{ __('Order') }}</th><th>{{ __('Date') }}</th><th>{{ __('Items') }}</th><th>{{ __('Payment') }}</th><th>{{ __('Shipping') }}</th><th>{{ __('Status') }}</th><th>{{ __('Total') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($orders as $order)
<tr><td><a href="{{ route('orders.show', $order) }}" class="text-orange-600 hover:underline">#{{ $order->order_number }}</a></td><td>{{ $order->created_at?->format('M d, Y') }}</td><td>{{ $order->order_items_count }}</td><td>{{ ucwords(str_replace('_', ' ', $order->payment_method ?? '—')) }}</td><td title="{{ $order->shipping_address }}">{{ \Illuminate\Support\Str::limit($order->shipping_address, 35) }}</td><td><x-order-status :status="$order->status" /></td><td>&dollar;{{ number_format($order->total_amount, 2) }}</td><td class="flex gap-2"><a href="{{ route('orders.show', $order) }}" class="text-orange-600 hover:underline">{{ __('View') }}</a>@if($order->status === 'pending')<button type="button" class="cancel-order-btn text-red-600 hover:underline" data-id="{{ $order->id }}">{{ __('Cancel') }}</button>@endif</td></tr>
@empty
<tr><td colspan="8" class="px-4 py-8 text-center text-zinc-500">{{ __('No orders found.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $orders, 'target' => '#orders-table'])
