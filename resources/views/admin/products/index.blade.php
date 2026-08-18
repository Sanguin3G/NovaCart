<x-layouts.app :title="__('Products')">
    <div class="nc-page">
        <header class="nc-page-header">
            <div>
                <p class="nc-eyebrow">{{ __('Admin management') }}</p>
                <h1 class="nc-title">{{ __('Products') }}</h1>
                <p class="nc-subtitle">{{ __('Keep the catalog accurate, available, and easy to browse.') }}</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="nc-btn-primary"><x-icon name="plus" width="17" height="17"/>{{ __('Create product') }}</a>
        </header>

        <form class="nc-toolbar" hx-get="{{ route('admin.products.data') }}" hx-target="#products-table" hx-trigger="change, keyup changed delay:300ms from:input" hx-indicator="#products-loading">
            <div class="nc-toolbar-group">
                <label class="sr-only" for="product-category">{{ __('Category') }}</label>
                <select id="product-category" name="category_id" class="nc-select"><option value="all">{{ __('All categories') }}</option>@foreach($categories as $id => $name)<option value="{{ $id }}" @selected(request('category_id') == $id)>{{ $name }}</option>@endforeach</select>
                <label class="sr-only" for="product-status">{{ __('Status') }}</label>
                <select id="product-status" name="status_filter" class="nc-select"><option value="all">{{ __('All statuses') }}</option><option value="active" @selected(request('status_filter') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status_filter') === 'inactive')>{{ __('Inactive') }}</option></select>
                <div class="relative w-full sm:max-w-xs"><x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="17" height="17"/><label class="sr-only" for="product-search">{{ __('Search products') }}</label><input id="product-search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search products…') }}" class="nc-search"></div>
            </div>
            <span id="products-loading" class="htmx-indicator text-xs font-medium text-orange-600">{{ __('Updating…') }}</span>
        </form>
        <section class="nc-card" aria-labelledby="products-list-title">
            <h2 id="products-list-title" class="sr-only">{{ __('Product list') }}</h2>
            <div id="products-table" hx-get="{{ route('admin.products.data') }}" hx-trigger="load" hx-swap="innerHTML"><div class="nc-state"><x-icon name="package" class="animate-pulse text-orange-500" width="24" height="24"/><p class="nc-state-copy">{{ __('Loading products…') }}</p></div></div>
        </section>
    </div>
</x-layouts.app>
