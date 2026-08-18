<x-layouts.app :title="__('Customers')">
    <div class="nc-page">
        <header class="nc-page-header">
            <div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Customers') }}</h1><p class="nc-subtitle">{{ __('Review customer accounts and keep access under control.') }}</p></div>
        </header>

        <form class="nc-toolbar" hx-get="{{ route('admin.users.data') }}" hx-target="#users-table" hx-trigger="change, keyup changed delay:300ms from:input" hx-indicator="#users-loading">
            <div class="nc-toolbar-group">
                <label class="sr-only" for="user-status">{{ __('Status') }}</label>
                <select id="user-status" name="status_filter" class="nc-select"><option value="all">{{ __('All accounts') }}</option><option value="active">{{ __('Active') }}</option><option value="inactive">{{ __('Suspended') }}</option></select>
                <div class="relative w-full sm:max-w-sm"><x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="17" height="17"/><label class="sr-only" for="user-search">{{ __('Search customers') }}</label><input id="user-search" name="search" placeholder="{{ __('Search by name or email…') }}" class="nc-search"></div>
            </div>
            <span id="users-loading" class="htmx-indicator text-xs font-medium text-orange-600">{{ __('Updating…') }}</span>
        </form>

        <section class="nc-card"><div id="users-table" hx-get="{{ route('admin.users.data') }}" hx-trigger="load" hx-swap="innerHTML"><div class="nc-state"><x-icon name="user" class="animate-pulse text-orange-500" width="24" height="24"/><p class="nc-state-copy">{{ __('Loading customers…') }}</p></div></div></section>
    </div>
</x-layouts.app>
