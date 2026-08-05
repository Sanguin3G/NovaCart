<div class="overflow-x-auto"><table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700"><thead><tr><th>#</th><th>{{ __('Image') }}</th><th>{{ __('Product') }}</th><th>{{ __('Rating') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($reviews as $review)
<tr><td>{{ $reviews->firstItem() + $loop->index }}</td><td><img src="{{ $review->product?->image_url }}" alt="{{ $review->product?->name }}" class="h-12 w-12 rounded object-cover"></td><td>{{ $review->product?->name }}</td><td class="text-amber-500">{{ str_repeat('★', $review->rating) }}</td><td><button class="edit-review-btn text-blue-600 hover:underline" data-review-id="{{ $review->id }}" data-product-id="{{ $review->product_id }}" data-rating="{{ $review->rating }}" data-body="{{ $review->body }}" data-product-name="{{ $review->product?->name }}">{{ __('Edit') }}</button></td></tr>
@empty
<tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">{{ __('You have not written any reviews yet.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $reviews, 'target' => '#mine-table'])
