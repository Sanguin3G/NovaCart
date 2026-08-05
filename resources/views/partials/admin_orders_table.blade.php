<div class="overflow-x-auto"><table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700"><thead><tr><th>#</th><th>{{ __('Order') }}</th><th>{{ __('Customer') }}</th><th>{{ __('Items') }}</th><th>{{ __('Total') }}</th><th>{{ __('Status') }}</th><th>{{ __('Date') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($orders as $order)
<tr><td>{{ $orders->firstItem() + $loop->index }}</td><td><a href="{{ route('admin.orders.show', $order) }}" class="text-orange-600 hover:underline">#{{ $order->order_number }}</a></td><td>{{ $order->user?->name }}</td><td>{{ $order->order_items_count }}</td><td>&dollar;{{ number_format($order->total_amount, 2) }}</td><td><x-order-status :status="$order->status" /></td><td>{{ $order->created_at?->format('M d, Y') }}</td><td><a href="{{ route('admin.orders.show', $order) }}" class="text-orange-600 hover:underline">{{ __('Detail') }}</a></td></tr>
@empty
<tr><td colspan="8" class="px-4 py-8 text-center text-zinc-500">{{ __('No orders found.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $orders, 'target' => '#orders-table'])
