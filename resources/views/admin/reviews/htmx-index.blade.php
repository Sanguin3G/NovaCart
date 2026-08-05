<x-layouts.app :title="__('Product Reviews')">
    <div class="mb-6 flex flex-wrap gap-3">
        <form class="flex flex-wrap gap-3" hx-get="{{ route('admin.reviews.data') }}" hx-target="#reviews-table" hx-trigger="change, keyup changed delay:300ms from:input">
            <select name="status_filter" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900"><option value="all">{{ __('All Status') }}</option><option value="active" @selected(request('status_filter') === 'active')>{{ __('Active') }}</option><option value="disabled" @selected(request('status_filter') === 'disabled')>{{ __('Disabled') }}</option></select>
            <input name="search" value="{{ request('search') }}" placeholder="{{ __('Search reviews…') }}" class="w-64 rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900">
        </form>
    </div>
    <div id="reviews-table" hx-get="{{ route('admin.reviews.data') }}" hx-trigger="load" hx-swap="innerHTML">
        <div class="py-12 text-center text-zinc-500">{{ __('Loading reviews…') }}</div>
    </div>
</x-layouts.app>
