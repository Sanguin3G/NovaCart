<x-layouts.app :title="__('Appearance | Settings')">
<div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <fieldset>
            <legend class="sr-only">Appearance</legend>
            <div class="inline-flex space-x-2">
                <x-button type="button" variant="secondary" before="phosphor-sun-fill" data-theme-option="light">{{ __('Light') }}</x-button>
                <x-button type="button" variant="secondary" before="phosphor-moon-fill" data-theme-option="dark">{{ __('Dark') }}</x-button>
                <x-button type="button" variant="secondary" before="phosphor-monitor-fill" data-theme-option="system">{{ __('System') }}</x-button>
            </div>
        </fieldset>
    </x-settings.layout>
</div>
</x-layouts.app>
