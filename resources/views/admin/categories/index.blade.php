<x-layouts.app :title="__('Categories')">
    <div class="nc-page">
        <header class="nc-page-header">
            <div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Categories') }}</h1><p class="nc-subtitle">{{ __('Organise the catalogue with clear, manageable product groups.') }}</p></div>
            <a href="{{ route('admin.categories.create') }}" class="nc-btn-primary"><x-icon name="plus" width="16" height="16" />{{ __('Create category') }}</a>
        </header>
        <form class="nc-toolbar" hx-get="{{ route('admin.categories.data') }}" hx-target="#categories-table" hx-trigger="change, keyup changed delay:300ms from:input" hx-push-url="true">
            <div class="nc-toolbar-group">
                <label class="sr-only" for="category-parent">{{ __('Parent category') }}</label>
                <select id="category-parent" name="parent_filter" class="nc-select"><option value="all">{{ __('All parent categories') }}</option><option value="none">{{ __('No parent') }}</option>@foreach($parentCategories as $id => $name)<option value="{{ $id }}" @selected(request('parent_filter') == $id)>{{ $name }}</option>@endforeach</select>
                <label class="sr-only" for="category-status">{{ __('Status') }}</label>
                <select id="category-status" name="status_filter" class="nc-select"><option value="all">{{ __('All statuses') }}</option><option value="active" @selected(request('status_filter') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status_filter') === 'inactive')>{{ __('Inactive') }}</option></select>
                <div class="relative w-full sm:max-w-sm"><x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="17" height="17" /><label class="sr-only" for="category-search">{{ __('Search categories') }}</label><input id="category-search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search categories…') }}" class="nc-search"></div>
            </div><span id="categories-loading" class="htmx-indicator text-xs font-medium text-orange-600">{{ __('Updating…') }}</span>
        </form>
        <section id="categories-table" class="nc-card" hx-get="{{ route('admin.categories.data') }}" hx-trigger="load" hx-swap="innerHTML"><div class="nc-state">{{ __('Loading categories…') }}</div></section>
    </div>
</x-layouts.app>
