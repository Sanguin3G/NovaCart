<div class="nc-table-wrap"><table class="nc-table"><thead><tr><th>#</th><th>{{ __('Product') }}</th><th>{{ __('Action') }}</th></tr></thead><tbody>
@forelse($products as $product)
<tr><td>{{ $products->firstItem() + $loop->index }}</td><td><div class="flex items-center gap-3"><img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-xl object-cover"><span class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</span></div></td><td><button class="nc-btn-link write-review-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"><x-icon name="edit" width="15" height="15"/>{{ __('Write review') }}</button></td></tr>
@empty
<tr><td colspan="3" class="nc-table-empty">{{ __('Nothing is waiting for review.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $products, 'target' => '#pending-table'])
