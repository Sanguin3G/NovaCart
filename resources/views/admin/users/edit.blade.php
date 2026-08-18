<x-layouts.app :title="__('Edit customer')">
    <div class="nc-page max-w-3xl">
        <header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Customer management') }}</p><h1 class="nc-title">{{ __('Edit customer') }}</h1><p class="nc-subtitle">{{ __('Update account details without exposing credentials.') }}</p></div><a href="{{ route('admin.users.index') }}" class="nc-btn-secondary"><x-icon name="chevron-left" width="16" height="16"/>{{ __('Back to customers') }}</a></header>
        @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/70 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="nc-card nc-card-body space-y-5">
            @csrf @method('PUT')
            <div><label for="name" class="mb-2 block text-sm font-semibold">{{ __('Name') }}</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required class="nc-control">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="email" class="mb-2 block text-sm font-semibold">{{ __('Email') }}</label><input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="nc-control">@error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div class="flex justify-end gap-3"><a href="{{ route('admin.users.index') }}" class="nc-btn-secondary">{{ __('Cancel') }}</a><button class="nc-btn-primary" type="submit"><x-icon name="check" width="17" height="17"/>{{ __('Save changes') }}</button></div>
        </form>
    </div>
</x-layouts.app>
