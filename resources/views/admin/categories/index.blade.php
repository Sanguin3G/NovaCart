<x-layouts.app :title="__('Categories')">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <form class="flex flex-wrap gap-3" hx-get="{{ route('admin.categories.data') }}" hx-target="#categories-table" hx-trigger="change, keyup changed delay:300ms from:input">
            <select name="parent_filter" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900"><option value="all">{{ __('All Parents') }}</option><option value="none">{{ __('No Parent') }}</option>@foreach($parentCategories as $id => $name)<option value="{{ $id }}" @selected(request('parent_filter') == $id)>{{ $name }}</option>@endforeach</select>
            <select name="status_filter" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900"><option value="all">{{ __('All Status') }}</option><option value="active" @selected(request('status_filter') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status_filter') === 'inactive')>{{ __('Inactive') }}</option></select>
            <input name="search" value="{{ request('search') }}" placeholder="{{ __('Search categories…') }}" class="w-64 rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900">
        </form>
        <a href="{{ route('admin.categories.create') }}" class="rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">{{ __('Create Category') }}</a>
    </div>
    <div id="categories-table" hx-get="{{ route('admin.categories.data') }}" hx-trigger="load" hx-swap="innerHTML">
        <div class="py-12 text-center text-zinc-500">{{ __('Loading categories…') }}</div>
    </div>
</x-layouts.app>
