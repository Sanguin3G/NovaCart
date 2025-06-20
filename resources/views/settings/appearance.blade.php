<x-layouts.app :title="__('Appearance | Settings')">
<div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <fieldset>
            <legend class="sr-only">Appearance</legend>
            <div class="inline-flex space-x-2">
                <x-button type="button" variant="secondary" before="phosphor-sun-fill" value="light" onclick="setAppearance(this.value)">{{ __('Light') }}</x-button>
                <x-button type="button" variant="secondary" before="phosphor-moon-fill" value="dark" onclick="setAppearance(this.value)">{{ __('Dark') }}</x-button>
                <x-button type="button" variant="secondary" before="phosphor-monitor-fill" value="system" onclick="setAppearance(this.value)">{{ __('System') }}</x-button>
            </div>
        </fieldset>
    </x-settings.layout>
</div>
</x-layouts.app>
