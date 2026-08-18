{{-- resources/views/partials/action_buttons.blade.php --}}
<div class="flex items-center gap-2">
    <a href="{{ $editUrl }}" class="nc-btn-ghost h-8 text-xs">
        <x-icon name="edit" width="15" height="15"/>{{ __('Edit') }}
    </a>
    <button
        type="button"
        class="nc-btn-ghost js-delete-btn {{ $deleteClass }} h-8 text-xs text-red-600 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-950/30"
        data-url="{{ $deleteUrl }}"
        {!! $deleteData ?? '' !!}
    >
        <x-icon name="trash" width="15" height="15"/>{{ __('Delete') }}
    </button>
</div>
