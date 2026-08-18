@props(['id','closable'=>true])

<div id="{{ $id }}" class="nc-modal" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">
    <div class="nc-modal-panel relative">
        {{ $slot }}
        @if($closable)
            <button type="button" data-modal-close class="absolute right-3 top-3 z-10 rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white" aria-label="{{ __('Close modal') }}">
                <x-icon name="close" width="18" height="18"/>
                <span class="sr-only">{{ __('Close modal') }}</span>
            </button>
        @endif
    </div>
</div>
