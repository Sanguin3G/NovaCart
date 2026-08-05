<x-layouts.app :title="__('Product Orders')">
    <div class="mb-6"><a href="{{ route('admin.products.index') }}" class="text-orange-600 hover:underline">← {{ __('Back to products') }}</a><h1 class="mt-2 text-2xl font-semibold">{{ __('Orders containing :product', ['product' => $product->name]) }}</h1></div>
    <form class="mb-4 flex flex-wrap gap-3" hx-get="{{ route('admin.products.orders', $product) }}" hx-target="#orders-table" hx-trigger="change, keyup changed delay:300ms from:input" hx-push-url="true">
        <input name="search" placeholder="{{ __('Search order #') }}" value="{{ request('search') }}" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900">
        <select name="status_filter" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900"><option value="all">{{ __('All Statuses') }}</option>@foreach(['pending','processing','shipped','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status_filter') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
    </form>
    <div id="orders-table">@include('partials.admin_orders_table', ['orders' => $orders])</div>
</x-layouts.app>
