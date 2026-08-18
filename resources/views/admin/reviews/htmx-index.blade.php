<x-layouts.app :title="__('Product Reviews')">
    <div class="nc-page">
        <header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Reviews') }}</h1><p class="nc-subtitle">{{ __('Moderate customer feedback while keeping the catalogue trustworthy.') }}</p></div></header>
        <form class="nc-toolbar" hx-get="{{ route('admin.reviews.data') }}" hx-target="#reviews-table" hx-trigger="change, keyup changed delay:300ms from:input" hx-indicator="#reviews-loading">
            <div class="nc-toolbar-group"><label class="sr-only" for="review-status">{{ __('Status') }}</label><select id="review-status" name="status_filter" class="nc-select"><option value="all">{{ __('All statuses') }}</option><option value="active" @selected(request('status_filter') === 'active')>{{ __('Active') }}</option><option value="disabled" @selected(request('status_filter') === 'disabled')>{{ __('Disabled') }}</option></select><div class="relative w-full sm:max-w-xs"><x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="17" height="17"/><label class="sr-only" for="review-search">{{ __('Search reviews') }}</label><input id="review-search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search reviews…') }}" class="nc-search"></div></div><span id="reviews-loading" class="htmx-indicator text-xs font-medium text-orange-600">{{ __('Updating…') }}</span>
        </form>
        <section class="nc-card"><div id="reviews-table" hx-get="{{ route('admin.reviews.data') }}" hx-trigger="load" hx-swap="innerHTML"><div class="nc-state"><x-icon name="star" class="animate-pulse text-orange-500" width="24" height="24"/><p class="nc-state-copy">{{ __('Loading reviews…') }}</p></div></div></section>
    </div>
    <x-modal id="review-detail-modal"><div id="review-detail-content"></div></x-modal>
</x-layouts.app>
