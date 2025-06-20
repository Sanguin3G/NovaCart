@props(['id','closable'=>true])

{{-- Overlay container hidden by default --}}
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    {{-- Modal box --}}
    <div class="relative w-full max-w-[95vw] max-h-[90vh] overflow-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl">
        {{ $slot }}
        @if($closable)
            <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700"
                    onclick="this.closest('[id={{ $id }}]').classList.add('hidden')">
                <x-phosphor-x width="20" height="20"/>
                <span class="sr-only">{{ __('Close modal') }}</span>
            </button>
        @endif
    </div>
</div>
