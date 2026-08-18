<div class="nc-table-wrap"><table class="nc-table"><thead><tr><th>{{ __('Order') }}</th><th>{{ __('Date') }}</th><th>{{ __('Items') }}</th><th>{{ __('Payment') }}</th><th>{{ __('Shipping') }}</th><th>{{ __('Status') }}</th><th>{{ __('Total') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($orders as $order)
<tr><td><a href="{{ route('orders.show', $order) }}" class="font-semibold text-orange-600 hover:underline">#{{ $order->order_number }}</a></td><td class="whitespace-nowrap">{{ $order->created_at?->format('M d, Y') }}</td><td>{{ $order->order_items_count }}</td><td>{{ ucwords(str_replace('_', ' ', $order->payment_method ?? '—')) }}</td><td title="{{ $order->shipping_address }}">{{ \Illuminate\Support\Str::limit($order->shipping_address, 35) }}</td><td><x-order-status :status="$order->status" /></td><td class="whitespace-nowrap font-semibold">&dollar;{{ number_format($order->total_amount, 2) }}</td><td><div class="flex flex-wrap gap-2"><a href="{{ route('orders.show', $order) }}" class="nc-btn-link"><x-icon name="eye" width="15" height="15"/>{{ __('View') }}</a>@if($order->status === 'pending')<button type="button" class="nc-btn-ghost cancel-order-btn h-8 text-xs text-red-600" data-id="{{ $order->id }}"><x-icon name="ban" width="15" height="15"/>{{ __('Cancel') }}</button>@endif</div></td></tr>
@empty
<tr><td colspan="8" class="nc-table-empty">{{ __('No orders found.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $orders, 'target' => '#orders-table'])
