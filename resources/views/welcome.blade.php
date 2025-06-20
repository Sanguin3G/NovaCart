<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 antialiased">

    <!-- Top navigation -->
    <header class="w-full max-w-5xl mx-auto flex items-center justify-between py-4 px-6">
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <x-app-logo-icon class="size-8 text-orange-600" />
            <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
        </a>

        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium hover:underline">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login-form') }}" class="text-sm font-medium hover:underline">{{ __('Log in') }}</a>

                    @if (Route::has('register-form'))
                        <a href="{{ route('register-form') }}" class="inline-flex items-center px-8 py-2 rounded-md text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 shadow">{{ __('Register') }}</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <div class="mx-auto max-w-4xl px-6 py-20 text-center">
                <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight mb-6">{{ __('Discover amazing products') }}</h1>
                <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-8">{{ __('Your one-stop shop for all your needs.') }}</p>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-8 py-3 rounded-md text-base font-semibold text-white bg-orange-600 hover:bg-orange-700 shadow">{{ __('Start Shopping') }}</a>

                    @guest
                        <a href="{{ route('login-form') }}" class="inline-flex items-center px-8 py-3 rounded-md text-base font-semibold text-orange-600 border border-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20">{{ __('Log in') }}</a>
                        <a href="{{ route('register-form') }}" class="inline-flex items-center px-8 py-3 rounded-md text-base font-semibold text-white bg-orange-600 hover:bg-orange-700 shadow">{{ __('Register') }}</a>
                    @endguest
                </div>
            </div>

            <!-- Decorative blobs -->
            <div class="pointer-events-none absolute -top-40 -left-40 size-[32rem] bg-orange-500/20 rounded-full filter blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -right-40 size-[32rem] bg-orange-600/20 rounded-full filter blur-3xl"></div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-16 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-5xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-12">{{ __('Why shop with us?') }}</h2>

                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="feature-card transform transition duration-700">
                        <div class="flex items-center justify-center size-14 rounded-xl bg-orange-100 text-orange-600 mb-4">
                            <x-phosphor-lightning-bold class="size-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Fast Shipping') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Get your orders delivered quickly anywhere.') }}</p>
                    </div>

                    <div class="feature-card transform transition duration-700">
                        <div class="flex items-center justify-center size-14 rounded-xl bg-orange-100 text-orange-600 mb-4">
                            <x-phosphor-shield-check class="size-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Secure Payments') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('We use industry-leading security standards.') }}</p>
                    </div>

                    <div class="feature-card transform transition duration-700">
                        <div class="flex items-center justify-center size-14 rounded-xl bg-orange-100 text-orange-600 mb-4">
                            <x-phosphor-smiley class="size-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Great Support') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Our team is here to help you 24/7.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Simple Intersection Observer animation -->
    <script type="module">
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.feature-card').forEach(card => {
            card.classList.add('opacity-0', 'translate-y-6');
            observer.observe(card);
        });
    </script>
</body>
</html>
