<x-layouts.app :title="__('My Reviews')">
    <div class="nc-page">
        <header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Your account') }}</p><h1 class="nc-title">{{ __('Reviews') }}</h1><p class="nc-subtitle">{{ __('Share useful feedback and manage what you have already written.') }}</p></div><div class="relative w-full sm:max-w-xs"><x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="17" height="17"/><label class="sr-only" for="reviews-search">{{ __('Search reviews') }}</label><input id="reviews-search" name="search" placeholder="{{ __('Search reviews…') }}" class="nc-search"></div></header>
        <div class="nc-card flex gap-1 overflow-x-auto p-2" role="tablist">
            <button data-tab="pending" class="tab-link rounded-xl bg-orange-50 px-4 py-2 text-sm font-semibold text-orange-700 dark:bg-orange-950/50 dark:text-orange-300" role="tab">{{ __('Pending reviews') }}</button>
            <button data-tab="mine" class="tab-link rounded-xl px-4 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" role="tab">{{ __('My reviews') }}</button>
        </div>
        <div id="tab-pending" hx-get="{{ route('reviews.pending') }}" hx-trigger="load" hx-target="#pending-table" hx-swap="innerHTML">
            <section class="nc-card"><div id="pending-table" class="nc-state">{{ __('Loading…') }}</div></section>
        </div>
        <div id="tab-mine" class="hidden" hx-get="{{ route('reviews.mine') }}" hx-trigger="load" hx-target="#mine-table" hx-swap="innerHTML">
            <section class="nc-card"><div id="mine-table" class="nc-state">{{ __('Loading…') }}</div></section>
        </div>
    </div>
    <x-modal id="review-modal" closable="true">
        <div class="bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 p-4 pr-14 text-white"><h2 id="review-modal-title" class="text-lg font-semibold"></h2></div>
        <form id="review-form" class="space-y-6 p-6">@csrf<input type="hidden" name="rating" id="review-rating" value="0"><input type="hidden" id="product-id-hidden"><div id="star-widget" class="flex gap-1 text-zinc-300">@for($i=1;$i<=5;$i++)<button type="button" data-value="{{ $i }}" class="star text-2xl">★</button>@endfor</div><textarea id="review-body" name="body" rows="4" class="w-full rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900" placeholder="{{ __('Share your experience') }}"></textarea><div class="text-right"><x-button type="submit" variant="primary">{{ __('Submit') }}</x-button></div></form>
    </x-modal>
    @push('scripts') @vite('resources/js/customer/reviews-page-htmx.js') @endpush
</x-layouts.app>
