<div class="nc-table-wrap">
    <table class="nc-table">
        <thead><tr><th>#</th><th>{{ __('Name') }}</th><th>{{ __('Category') }}</th><th>{{ __('Price') }}</th><th>{{ __('Stock') }}</th><th>{{ __('Status') }}</th><th>{{ __('Updated') }}</th><th>{{ __('Orders') }}</th><th>{{ __('Actions') }}</th></tr></thead>
        <tbody>
        @forelse($products as $product)
            <tr><td>{{ $products->firstItem() + $loop->index }}</td><td class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</td><td>{{ $product->category_name ?? '—' }}</td><td class="whitespace-nowrap">{{ $product->formatted_price }}</td><td><span class="{{ $product->stock > 0 ? 'nc-badge-success' : 'nc-badge-danger' }}">{{ $product->stock }}</span></td><td>@include('components.status-toggle', ['url' => route('admin.products.toggleStatus', $product), 'checked' => $product->is_active])</td><td class="whitespace-nowrap">{{ $product->updated_at?->format('M d, Y') }}</td><td><a href="{{ route('admin.products.orders', $product) }}" class="nc-btn-link"><x-icon name="receipt" width="15" height="15"/>{{ __('View') }}</a></td><td>@include('partials.action_buttons', ['editUrl' => route('admin.products.edit', $product), 'deleteUrl' => route('admin.products.destroy', $product), 'deleteClass' => 'delete-product'])</td></tr>
        @empty
            <tr><td colspan="9" class="nc-table-empty">{{ __('No products found.') }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@include('components.pagination.htmx', ['paginator' => $products, 'target' => '#products-table'])
