<div class="nc-table-wrap"><table class="nc-table"><thead><tr><th>#</th><th>{{ __('Product') }}</th><th>{{ __('Rating') }}</th><th>{{ __('Action') }}</th></tr></thead><tbody>
@forelse($reviews as $review)
<tr><td>{{ $reviews->firstItem() + $loop->index }}</td><td><div class="flex items-center gap-3"><img src="{{ $review->product?->image_url }}" alt="{{ $review->product?->name }}" class="h-12 w-12 rounded-xl object-cover"><span class="font-semibold text-gray-900 dark:text-white">{{ $review->product?->name ?? __('Deleted product') }}</span></div></td><td><span class="text-amber-500" aria-label="{{ $review->rating }} out of 5">{{ str_repeat('★', $review->rating) }}</span></td><td><button class="nc-btn-link edit-review-btn" data-review-id="{{ $review->id }}" data-product-id="{{ $review->product_id }}" data-rating="{{ $review->rating }}" data-body="{{ $review->body }}" data-product-name="{{ $review->product?->name }}"><x-icon name="edit" width="15" height="15"/>{{ __('Edit') }}</button></td></tr>
@empty
<tr><td colspan="4" class="nc-table-empty">{{ __('You have not written any reviews yet.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $reviews, 'target' => '#mine-table'])
