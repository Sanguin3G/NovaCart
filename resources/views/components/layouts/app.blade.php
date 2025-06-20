<x-layouts.app.sidebar :title="$title ?? null">
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $header }}
            </div>
        </header>
    @endisset
    <x-container class="[grid-area:main] max-w-full py-6 lg:py-8">
        @if (session('status') === 'verification-link-sent')
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        @if (request()->query('verified'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800">
                {{ __('Your email has been verified!') }}
            </div>
        @endif

        {{ $slot }}
    </x-container>
</x-layouts.app.sidebar>
