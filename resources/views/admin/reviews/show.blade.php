<div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 bg-gray-100 dark:bg-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">#{{ $review->id }} – {{ $review->product->name }}</h3>

        <button type="button" onclick="document.getElementById('review-detail-modal').close()"
                class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!-- Body -->
    <div class="px-6 py-4 space-y-4 text-gray-700 dark:text-gray-300">
        <div class="flex items-center space-x-2">
            <span class="font-medium">{{ __('Reviewer') }}:</span>
            <span>{{ $review->user?->name ?? $review->reviewer_name }}</span>
        </div>

        <div class="flex items-center space-x-2">
            <span class="font-medium">{{ __('Rating') }}:</span>
            <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}</span>
        </div>

        <div>
            <p class="font-medium mb-1">{{ __('Comment') }}:</p>
            <p class="whitespace-pre-line">{{ $review->body }}</p>
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Created at') }}: {{ $review->created_at->format('Y-m-d H:i') }}</p>

        @if($review->trashed())
            <span class="inline-block px-2 py-1 bg-red-600 text-white text-xs rounded">{{ __('Disabled') }}</span>
        @endif
    </div>
</div> 