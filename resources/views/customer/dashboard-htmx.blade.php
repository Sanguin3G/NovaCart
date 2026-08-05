<x-layouts.app :title="__('Dashboard')">
    <div class="mx-auto max-w-7xl space-y-8 py-8">
        <section class="rounded-2xl border border-orange-200 bg-gradient-to-r from-orange-50 to-amber-50 p-8 dark:border-orange-900 dark:from-orange-950/40 dark:to-amber-950/30">
            <p class="text-sm font-medium text-orange-700 dark:text-orange-300">{{ __('Welcome back') }}</p>
            <h1 class="mt-1 text-3xl font-bold">{{ Auth::user()?->name }}</h1>
            <p class="mt-2 text-zinc-600 dark:text-zinc-300">{{ __('Here is a quick look at your shopping activity.') }}</p>
        </section>
        <div class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('products.index') }}" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm hover:border-orange-400 dark:border-zinc-700 dark:bg-zinc-900"><x-icon name="heroicon-o-shopping-bag" class="mb-3 size-7 text-orange-600"/><span class="font-semibold">{{ __('Browse Products') }}</span></a>
            <a href="{{ route('orders.index') }}" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm hover:border-orange-400 dark:border-zinc-700 dark:bg-zinc-900"><x-icon name="phosphor-receipt" class="mb-3 size-7 text-blue-600"/><span class="font-semibold">{{ __('My Orders') }}</span><span class="mt-1 block text-2xl font-bold">{{ $customerTotalOrders }}</span></a>
            <a href="{{ route('settings.profile.edit') }}" class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm hover:border-orange-400 dark:border-zinc-700 dark:bg-zinc-900"><x-icon name="heroicon-o-user" class="mb-3 size-7 text-emerald-600"/><span class="font-semibold">{{ __('Account Settings') }}</span></a>
        </div>
        <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-semibold">{{ __('Recent Orders') }}</h2><a href="{{ route('orders.index') }}" class="text-sm text-orange-600 hover:underline">{{ __('View all') }}</a></div>
            @forelse($recentOrders as $order)
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-zinc-200 py-4 dark:border-zinc-700"><a href="{{ route('orders.show', $order) }}" class="font-medium text-orange-600 hover:underline">#{{ $order->order_number }}</a><span class="text-sm text-zinc-500">{{ $order->created_at?->format('M d, Y') }}</span><x-order-status :status="$order->status"/><span class="font-semibold">&dollar;{{ number_format($order->total_amount, 2) }}</span></div>
            @empty
                <p class="py-8 text-center text-zinc-500">{{ __('You have no orders yet.') }}</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>
