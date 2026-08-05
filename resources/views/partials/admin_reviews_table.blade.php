<div class="overflow-x-auto"><table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700"><thead><tr><th>#</th><th>{{ __('Product') }}</th><th>{{ __('Reviewer') }}</th><th>{{ __('Rating') }}</th><th>{{ __('Status') }}</th><th>{{ __('Created') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($reviews as $review)
<tr><td>{{ $reviews->firstItem() + $loop->index }}</td><td>{{ $review->product?->name }}</td><td>{{ $review->user?->name ?? $review->reviewer_name }}</td><td class="text-amber-500">{{ str_repeat('★', $review->rating) }}</td><td><span class="rounded-full px-2 py-1 text-xs {{ $review->trashed() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $review->trashed() ? __('Disabled') : __('Active') }}</span></td><td>{{ $review->created_at?->format('M d, Y') }}</td><td class="flex gap-3"><a href="{{ route('admin.reviews.show', $review) }}" class="text-blue-600 hover:underline">{{ __('Detail') }}</a><button type="button" class="js-review-toggle text-orange-600 hover:underline" data-url="{{ route('admin.reviews.disable', $review) }}">{{ $review->trashed() ? __('Enable') : __('Disable') }}</button></td></tr>
@empty
<tr><td colspan="7" class="px-4 py-8 text-center text-zinc-500">{{ __('No reviews found.') }}</td></tr>
@endforelse
</tbody></table></div>
@include('components.pagination.htmx', ['paginator' => $reviews, 'target' => '#reviews-table'])
