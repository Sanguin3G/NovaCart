<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800"><tr><th class="px-4 py-3 text-left text-xs uppercase">#</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Name') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Category') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Price') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Stock') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Status') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Updated') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Orders') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Actions') }}</th></tr></thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
        @forelse($products as $product)
            <tr class="hover:bg-orange-50/50 dark:hover:bg-zinc-800"><td class="px-4 py-3">{{ $products->firstItem() + $loop->index }}</td><td class="px-4 py-3 font-medium">{{ $product->name }}</td><td class="px-4 py-3">{{ $product->category_name }}</td><td class="px-4 py-3">{{ $product->formatted_price }}</td><td class="px-4 py-3">{{ $product->stock }}</td><td class="px-4 py-3">@include('components.status-toggle', ['url' => route('admin.products.toggleStatus', $product), 'checked' => $product->is_active])</td><td class="px-4 py-3 whitespace-nowrap">{{ $product->updated_at?->format('M d, Y') }}</td><td class="px-4 py-3"><a href="{{ route('admin.products.orders', $product) }}" class="text-orange-600 hover:underline">{{ __('View') }}</a></td><td class="px-4 py-3">@include('partials.action_buttons', ['editUrl' => route('admin.products.edit', $product), 'deleteUrl' => route('admin.products.destroy', $product), 'deleteClass' => 'delete-product'])</td></tr>
        @empty
            <tr><td colspan="9" class="px-4 py-8 text-center text-zinc-500">{{ __('No products found.') }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@include('components.pagination.htmx', ['paginator' => $products, 'target' => '#products-table'])
