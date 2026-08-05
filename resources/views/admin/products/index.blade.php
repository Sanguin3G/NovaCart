<x-layouts.app :title="__('Products')">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <form class="flex flex-wrap gap-3" hx-get="{{ route('admin.products.data') }}" hx-target="#products-table" hx-trigger="change, keyup changed delay:300ms from:input">
            <select name="category_id" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900"><option value="all">{{ __('All Categories') }}</option>@foreach($categories as $id => $name)<option value="{{ $id }}" @selected(request('category_id') == $id)>{{ $name }}</option>@endforeach</select>
            <select name="status_filter" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900"><option value="all">{{ __('All Status') }}</option><option value="active" @selected(request('status_filter') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status_filter') === 'inactive')>{{ __('Inactive') }}</option></select>
            <input name="search" value="{{ request('search') }}" placeholder="{{ __('Search products…') }}" class="w-64 rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900">
        </form>
        <a href="{{ route('admin.products.create') }}" class="rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">{{ __('Create Product') }}</a>
    </div>
    <div id="products-table" hx-get="{{ route('admin.products.data') }}" hx-trigger="load" hx-swap="innerHTML">
        <div class="py-12 text-center text-zinc-500">{{ __('Loading products…') }}</div>
    </div>
</x-layouts.app>
