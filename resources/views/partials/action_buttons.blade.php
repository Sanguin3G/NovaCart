{{-- resources/views/partials/action_buttons.blade.php --}}
<div class="flex space-x-2">
    <a href="{{ $editUrl }}" class="text-blue-600 hover:text-blue-800">
        {{ __('Edit') }}
    </a>
    <button
        type="button"
        class="js-delete-btn {{ $deleteClass }} text-red-600 hover:text-red-800"
        data-url="{{ $deleteUrl }}"
        {!! $deleteData ?? '' !!}
    >
        {{ __('Delete') }}
    </button>
</div> 