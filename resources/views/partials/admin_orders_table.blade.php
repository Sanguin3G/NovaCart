<div class="nc-table-wrap"><table class="nc-table"><thead><tr><th>#</th><th>{{ __('Order') }}</th><th>{{ __('Customer') }}</th><th>{{ __('Items') }}</th><th>{{ __('Total') }}</th><th>{{ __('Status') }}</th><th>{{ __('Date') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($orders as $order)
<tr><td>{{ $orders->firstItem() + $loop->index }}</td><td><a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-orange-600 hover:underline">#{{ $order->order_number }}</a></td><td>{{ $order->user?->name ?? __('Guest') }}</td><td>{{ $order->order_items_count }}</td><td class="whitespace-nowrap font-semibold">&dollar;{{ number_format($order->total_amount, 2) }}</td><td><x-order-status :status="$order->status" /></td><td class="whitespace-nowrap">{{ $order->created_at?->format('M d, Y') }}</td><td><a href="{{ route('admin.orders.show', $order) }}" class="nc-btn-link"><x-icon name="eye" width="15" height="15"/>{{ __('Details') }}</a></td></tr>
@empty
<tr><td colspan="8" class="nc-table-empty">{{ __('No orders found.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $orders, 'target' => '#orders-table'])
