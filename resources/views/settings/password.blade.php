<x-layouts.app :title="__('Password | Settings')">
<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <x-form id="password-form" method="put" action="{{ route('settings.password.update') }}" class="mt-6 space-y-6">
            <x-input
                type="password"
                name="current_password"
                x-model="current_password"
                :label="__('Current password')"
                required
                autocomplete="current-password"
            />
            <x-input
                type="password"
                name="password"
                x-model="password"
                :label="__('New password')"
                required
                autocomplete="new-password"
            />
            <x-input
                type="password"
                name="password_confirmation"
                x-model="password_confirmation"
                :label="__('Confirm Password')"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button type="submit" class="w-full">{{ __('Save') }}</x-button>
                </div>
            </div>
        </x-form>
    </x-settings.layout>
</section>
</x-layouts.app>
