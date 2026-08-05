<div class="overflow-x-auto"><table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700"><thead><tr><th>#</th><th>{{ __('Image') }}</th><th>{{ __('Product') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($products as $product)
<tr><td>{{ $products->firstItem() + $loop->index }}</td><td><img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover"></td><td>{{ $product->name }}</td><td><a href="{{ route('products.show', $product) }}#write-review" class="text-orange-600 hover:underline">{{ __('Review') }}</a></td></tr>
@empty
<tr><td colspan="4" class="px-4 py-8 text-center text-zinc-500">{{ __('Nothing is waiting for review.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $products, 'target' => '#todo-reviews-table'])
