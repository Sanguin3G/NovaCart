<x-layouts.app :title="__('Orders')">
    <div class="nc-page">
        <header class="nc-page-header">
            <div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Orders') }}</h1><p class="nc-subtitle">{{ __('Review customer purchases and keep fulfilment moving.') }}</p></div>
        </header>
        <form class="nc-toolbar" hx-get="{{ route('admin.orders.data') }}" hx-target="#orders-table" hx-trigger="change, keyup changed delay:300ms from:input" hx-indicator="#orders-loading">
            <div class="nc-toolbar-group">
                <label class="sr-only" for="order-status">{{ __('Status') }}</label>
                <select id="order-status" name="status_filter" class="nc-select"><option value="all">{{ __('All statuses') }}</option>@foreach(['pending','processing','shipped','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status_filter') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
                <div class="relative w-full sm:max-w-xs"><x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="17" height="17"/><label class="sr-only" for="order-search">{{ __('Search orders') }}</label><input id="order-search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by order number…') }}" class="nc-search"></div>
            </div>
            <span id="orders-loading" class="htmx-indicator text-xs font-medium text-orange-600">{{ __('Updating…') }}</span>
        </form>
        <section class="nc-card"><div id="orders-table" hx-get="{{ route('admin.orders.data') }}" hx-trigger="load" hx-swap="innerHTML"><div class="nc-state"><x-icon name="receipt" class="animate-pulse text-orange-500" width="24" height="24"/><p class="nc-state-copy">{{ __('Loading orders…') }}</p></div></div></section>
    </div>
</x-layouts.app>
