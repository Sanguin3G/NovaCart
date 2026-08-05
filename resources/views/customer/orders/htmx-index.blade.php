<x-layouts.app :title="__('My Orders')">
    <div class="mb-6 flex flex-wrap gap-3">
        <form class="flex flex-wrap gap-3" hx-get="{{ route('orders.data') }}" hx-target="#orders-table" hx-trigger="change, keyup changed delay:300ms from:input">
            <select name="status_filter" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900"><option value="all">{{ __('All Statuses') }}</option>@foreach(['pending','processing','shipped','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status_filter') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
            <input name="search" value="{{ request('search') }}" placeholder="{{ __('Search by Order #') }}" class="w-64 rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900">
        </form>
    </div>
    <div id="orders-table" hx-get="{{ route('orders.data') }}" hx-trigger="load" hx-swap="innerHTML">
        <div class="py-12 text-center text-zinc-500">{{ __('Loading orders…') }}</div>
    </div>
</x-layouts.app>
