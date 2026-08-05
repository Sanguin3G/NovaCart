<x-layouts.app :title="__('My Reviews')">
    <div class="mx-auto max-w-6xl py-8">
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-2xl font-semibold">{{ __('Reviews') }}</h1>
            <input id="reviews-search" name="search" placeholder="{{ __('Search reviews…') }}" class="w-64 rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900">
        </div>
        <div class="mb-4 flex gap-6 border-b border-zinc-200 dark:border-zinc-700">
            <button data-tab="pending" class="tab-link border-b-2 border-orange-500 px-3 py-2 font-medium text-orange-600">{{ __('Pending') }}</button>
            <button data-tab="mine" class="tab-link border-b-2 border-transparent px-3 py-2 font-medium text-zinc-500">{{ __('My Reviews') }}</button>
        </div>
        <div id="tab-pending" hx-get="{{ route('reviews.pending') }}" hx-trigger="load" hx-target="#pending-table" hx-swap="innerHTML">
            <div id="pending-table" class="py-8 text-center text-zinc-500">{{ __('Loading…') }}</div>
        </div>
        <div id="tab-mine" class="hidden" hx-get="{{ route('reviews.mine') }}" hx-trigger="load" hx-target="#mine-table" hx-swap="innerHTML">
            <div id="mine-table" class="py-8 text-center text-zinc-500">{{ __('Loading…') }}</div>
        </div>
    </div>
    <x-modal id="review-modal" closable="true">
        <div class="bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 p-4 text-white"><h2 id="review-modal-title" class="text-lg font-semibold"></h2></div>
        <form id="review-form" class="space-y-6 p-6">@csrf<input type="hidden" name="rating" id="review-rating" value="0"><input type="hidden" id="product-id-hidden"><div id="star-widget" class="flex gap-1 text-zinc-300">@for($i=1;$i<=5;$i++)<button type="button" data-value="{{ $i }}" class="star text-2xl">★</button>@endfor</div><textarea id="review-body" name="body" rows="4" class="w-full rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900" placeholder="{{ __('Share your experience') }}"></textarea><div class="text-right"><x-button type="submit" variant="primary">{{ __('Submit') }}</x-button></div></form>
    </x-modal>
    @push('scripts') @vite('resources/js/customer/reviews-page-htmx.js') @endpush
</x-layouts.app>
