<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="layout sidebar min-h-screen bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
<x-sidebar sticky stashable class="border-r border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
    <x-sidebar.toggle class="lg:hidden w-10 p-0">
        <x-phosphor-x aria-hidden="true" width="20" height="20"/>
    </x-sidebar.toggle>

    @php($isAdmin = auth('admin')->check())
    @php($currentUser = auth('admin')->user() ?? auth('web')->user())
    @php($dashboardUrl = $isAdmin ? route('dashboard') : route('customer.dashboard'))
    <a href="{{ $dashboardUrl }}" class="mr-5 flex items-center space-x-2">
        <x-app-logo/>
    </a>

    @if($currentUser)
        <x-navlist>
            <x-navlist.group :heading="__('Platform')">
                <x-navlist.item before="phosphor-house-line" :href="$dashboardUrl"
                                :current="auth('admin')->check() ? request()->routeIs('dashboard') : request()->routeIs('customer.dashboard')">
                    {{ __('Dashboard') }}
                </x-navlist.item>
            </x-navlist.group>
        </x-navlist>
    @endif

    @if(!$currentUser)
        <x-navlist>
            <x-navlist.group :heading="__('Shop')">
                <x-navlist.item before="heroicon-o-shopping-bag" :href="route('products.index')"
                                :current="request()->routeIs('products.*')">
                    {{ __('Products') }}
                </x-navlist.item>
            </x-navlist.group>
        </x-navlist>
    @endif

    @auth('admin')
        <x-navlist>
            <x-navlist.group :heading="__('Admin Management')">
                <x-navlist.item before="phosphor-package" :href="route('admin.products.index')"
                                :current="request()->routeIs('admin.products.*')">
                    {{ __('Products') }}
                </x-navlist.item>
                <x-navlist.item before="phosphor-tag" :href="route('admin.categories.index')"
                                :current="request()->routeIs('admin.categories.*')">
                    {{ __('Categories') }}
                </x-navlist.item>
                <x-navlist.item before="phosphor-receipt" :href="route('admin.orders.index')"
                                 :current="request()->routeIs('admin.orders.*')">
                    {{ __('Orders') }}
                </x-navlist.item>
                <x-navlist.item before="phosphor-star" :href="route('admin.reviews.index')"
                                :current="request()->routeIs('admin.reviews.*')">
                    {{ __('Reviews') }}
                </x-navlist.item>
            </x-navlist.group>
        </x-navlist>
    @endauth

    @if($currentUser)
        @unless($isAdmin)
            <x-navlist>
                <x-navlist.group :heading="__('Shop')">
                    <x-navlist.item before="heroicon-o-shopping-bag" :href="route('products.index')"
                                    :current="request()->routeIs('products.*')">
                        {{ __('Products') }}
                    </x-navlist.item>
                    <x-navlist.item before="phosphor-shopping-cart" :href="route('cart.view')"
                                    :current="request()->routeIs('cart.*')">
                        {{ __('Cart') }}
                        <span id="cart-item-count" class="ml-auto inline-flex items-center justify-center rounded-full bg-orange-600 text-white text-[10px] px-1.5 py-0.5 {{ ($cartItemCount ?? 0) > 0 ? '' : 'hidden' }}">{{ $cartItemCount ?? 0 }}</span>
                    </x-navlist.item>
                    <x-navlist.item before="phosphor-receipt" :href="route('orders.index')"
                                    :current="request()->routeIs('orders.*')">
                        {{ __('Orders') }}
                    </x-navlist.item>
                    <x-navlist.item before="phosphor-star" :href="route('reviews.index')"
                                    :current="request()->routeIs('reviews.*') && !request()->routeIs('admin.*')">
                        {{ __('Reviews') }}
                    </x-navlist.item>
                </x-navlist.group>
            </x-navlist>
        @endunless
    @endif

    <x-spacer/>

    <x-navlist>
        <x-navlist.item before="phosphor-git-pull-request" href="https://www.youtube.com/watch?v=xvFZjo5PgG0"
                        target="_blank">
            {{ __('Repository') }}
        </x-navlist.item>

        <x-navlist.item before="phosphor-book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
            {{ __('Documentation') }}
        </x-navlist.item>
    </x-navlist>

    @if($currentUser)
        <x-popover align="bottom" justify="left">
            <button type="button"
                    class="w-full group flex items-center rounded-lg p-1 hover:bg-gray-800/5 dark:hover:bg-white/10">
                    <span class="shrink-0 size-8 bg-gray-200 rounded-sm overflow-hidden dark:bg-gray-700">
                        <span class="w-full h-full flex items-center justify-center text-sm">
                            {{ $currentUser->initials() }}
                        </span>
                    </span>
                <span
                    class="ml-2 text-sm text-gray-500 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white font-medium truncate">
                        {{ $currentUser->name }}
                    </span>
                <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                        <x-phosphor-caret-up-down aria-hidden="true" width="16" height="16"
                                                  class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white"/>
                    </span>
            </button>
            <x-slot:menu class="w-full">
                <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-gray-200 text-black dark:bg-gray-700 dark:text-white">
                                {{ $currentUser->initials() }}
                            </span>
                        </span>

                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-semibold">{{ $currentUser->name }}</span>
                        <span class="truncate text-xs">{{ $currentUser->email }}</span>
                    </div>
                </div>
                <x-popover.separator/>
                <x-popover.item before="phosphor-gear-fine"
                                href="/settings/profile">{{ __('Settings') }}</x-popover.item>
                <x-popover.separator/>
                <x-form method="post" action="{{ route('logout') }}" class="w-full flex">
                    <x-popover.item before="phosphor-sign-out">{{ __('Log Out') }}</x-popover.item>
                </x-form>
            </x-slot:menu>
        </x-popover>
    @else
        <div class="px-4 py-2 flex flex-col gap-2">
            <a href="{{ route('login.form') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 w-full">{{ __('Log in') }}</a>
            @if(Route::has('register-form'))
                <a href="{{ route('register-form') }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-orange-600 border border-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 w-full">{{ __('Register') }}</a>
            @endif
        </div>
    @endif
</x-sidebar>

<!-- Mobile User Menu -->
<x-header class="lg:hidden">
    <x-container class="min-h-14 flex items-center">
        <x-sidebar.toggle class="lg:hidden w-10 p-0">
            <x-phosphor-list aria-hidden="true" width="20" height="20"/>
        </x-sidebar.toggle>

        <x-spacer/>

        @if($currentUser)
            <x-popover align="top" justify="right">
                <button type="button"
                        class="w-full group flex items-center rounded-lg p-1 hover:bg-gray-800/5 dark:hover:bg-white/10">
                            <span class="shrink-0 size-8 bg-gray-200 rounded-sm overflow-hidden dark:bg-gray-700">
                                <span class="w-full h-full flex items-center justify-center text-sm">
                                    {{ $currentUser->initials() }}
                                </span>
                            </span>
                    <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                                <x-phosphor-caret-down width="16" height="16"
                                                       class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white"/>
                            </span>
                </button>
                <x-slot:menu>
                    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-gray-200 text-black dark:bg-gray-700 dark:text-white">
                                        {{ $currentUser->initials() }}
                                    </span>
                                </span>
                        <div class="grid flex-1 text-left text-sm leading-tight">
                            <span class="truncate font-semibold">{{ $currentUser->name }}</span>
                            <span class="truncate text-xs">{{ $currentUser->email }}</span>
                        </div>
                    </div>
                    <x-popover.separator/>
                    <x-popover.item before="phosphor-gear-fine"
                                    href="/settings/profile">{{ __('Settings') }}</x-popover.item>
                    <x-popover.separator/>
                    <x-form method="post" action="{{ route('logout') }}" class="w-full flex">
                        <x-popover.item before="phosphor-sign-out">{{ __('Log Out') }}</x-popover.item>
                    </x-form>
                </x-slot:menu>
            </x-popover>
        @else
            <div class="flex gap-3 ml-auto">
                <a href="{{ route('login.form') }}" class="text-sm font-medium hover:underline">{{ __('Log in') }}</a>
                <a href="{{ route('register-form') }}"
                   class="inline-flex items-center px-4 py-1.5 rounded-md text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 shadow">{{ __('Register') }}</a>
            </div>
        @endif
    </x-container>
</x-header>

<!-- Loading Overlay -->
<div id="loading-overlay"
     class="fixed inset-0 flex items-center justify-center bg-black/50 dark:bg-black/70 z-[9999] opacity-0 invisible pointer-events-none transition-opacity duration-200 ease-in-out">
    <div class="animate-spin rounded-full h-16 w-16 border-4 border-t-4 border-white"></div>
</div>

{{ $slot }}

@if(session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Toast.success(@json(session('status')));
        });
    </script>
@endif

@stack('scripts')

</body>
</html>
