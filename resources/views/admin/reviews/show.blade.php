<div class="p-6">
    <div class="mb-6 flex items-start justify-between gap-4 border-b border-gray-200 pb-4 pr-14 dark:border-gray-700">
        <div><p class="nc-eyebrow">{{ __('Review #:id', ['id' => $review->id]) }}</p><h2 id="review-detail-modal-title" class="mt-1 text-xl font-semibold text-gray-950 dark:text-white">{{ $review->product?->name ?? __('Product review') }}</h2></div>
        <span class="mt-1 rounded-full bg-orange-50 p-2 text-orange-500 dark:bg-orange-950/40" aria-hidden="true"><x-icon name="star" width="20" height="20"/></span>
    </div>
    <dl class="grid gap-4 sm:grid-cols-2">
        <div><dt class="nc-eyebrow">{{ __('Reviewer') }}</dt><dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $review->user?->name ?? $review->reviewer_name ?? __('Unknown customer') }}</dd></div>
        <div><dt class="nc-eyebrow">{{ __('Rating') }}</dt><dd class="mt-1 text-lg text-amber-500">{{ str_repeat('★', $review->rating) }}<span class="ml-2 text-sm text-gray-500">{{ $review->rating }}/5</span></dd></div>
        <div class="sm:col-span-2"><dt class="nc-eyebrow">{{ __('Comment') }}</dt><dd class="mt-2 whitespace-pre-line rounded-xl bg-gray-50 p-4 text-gray-700 dark:bg-gray-950 dark:text-gray-300">{{ $review->body }}</dd></div>
        <div><dt class="nc-eyebrow">{{ __('Created') }}</dt><dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $review->created_at?->format('M d, Y · H:i') }}</dd></div>
        <div><dt class="nc-eyebrow">{{ __('Status') }}</dt><dd class="mt-1"><span class="{{ $review->trashed() ? 'nc-badge-danger' : 'nc-badge-success' }}">{{ $review->trashed() ? __('Disabled') : __('Active') }}</span></dd></div>
    </div>
</div>
