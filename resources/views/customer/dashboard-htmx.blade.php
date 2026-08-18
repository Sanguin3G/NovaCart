<x-layouts.app :title="__('Dashboard')">
    <div class="nc-page">
        <section class="rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-50 via-white to-amber-50 p-6 shadow-sm sm:p-8 dark:border-orange-900/70 dark:from-orange-950/40 dark:via-gray-900 dark:to-amber-950/30">
            <p class="nc-eyebrow text-orange-700 dark:text-orange-300">{{ __('Customer dashboard') }}</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">{{ __('Welcome back, :name', ['name' => Auth::user()?->name]) }}</h1>
            <p class="mt-2 max-w-xl text-sm text-gray-600 dark:text-gray-300">{{ __('A quick look at your shopping activity, orders, and account shortcuts.') }}</p>
        </section>
        <div class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('products.index') }}" class="nc-card group p-5 transition hover:-translate-y-0.5 hover:border-orange-300"><x-icon name="shopping-bag" class="mb-4 text-orange-500" width="26" height="26"/><span class="block font-semibold text-gray-950 dark:text-white">{{ __('Browse products') }}</span><span class="mt-1 block text-sm text-gray-500 dark:text-gray-400">{{ __('Find your next favourite.') }}</span></a>
            <a href="{{ route('orders.index') }}" class="nc-card group p-5 transition hover:-translate-y-0.5 hover:border-orange-300"><x-icon name="receipt" class="mb-4 text-blue-500" width="26" height="26"/><span class="block font-semibold text-gray-950 dark:text-white">{{ __('My orders') }}</span><span class="mt-1 block text-2xl font-bold text-gray-950 dark:text-white">{{ $customerTotalOrders }}</span></a>
            <a href="{{ route('settings.profile.edit') }}" class="nc-card group p-5 transition hover:-translate-y-0.5 hover:border-orange-300"><x-icon name="user" class="mb-4 text-emerald-500" width="26" height="26"/><span class="block font-semibold text-gray-950 dark:text-white">{{ __('Account settings') }}</span><span class="mt-1 block text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your details.') }}</span></a>
        </div>
        <section class="nc-card nc-card-body">
            <div class="mb-4 flex items-center justify-between"><h2 class="text-lg font-semibold text-gray-950 dark:text-white">{{ __('Recent orders') }}</h2><a href="{{ route('orders.index') }}" class="nc-btn-link">{{ __('View all') }}<x-icon name="chevron-right" width="15" height="15"/></a></div>
            @forelse($recentOrders as $order)
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 py-4 dark:border-gray-700"><a href="{{ route('orders.show', $order) }}" class="font-semibold text-orange-600 hover:underline">#{{ $order->order_number }}</a><span class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at?->format('M d, Y') }}</span><x-order-status :status="$order->status"/><span class="font-semibold text-gray-950 dark:text-white">&dollar;{{ number_format($order->total_amount, 2) }}</span></div>
            @empty
                <div class="nc-state">{{ __('You have no orders yet.') }}</div>
            @endforelse
        </section>
    </div>
</x-layouts.app>
