@props(['url', 'checked' => false])

<div class="relative inline-block w-12 h-6">
    <input
        id="toggle-{{ md5($url) }}"
        type="checkbox"
        class="js-status-toggle peer absolute w-0 h-0 opacity-0"
        data-url="{{ $url }}"
        {{ $checked ? 'checked' : '' }}
    />
    <label
        for="toggle-{{ md5($url) }}"
        class="block bg-gray-300 peer-checked:bg-green-400 w-12 h-6 rounded-full cursor-pointer transition-colors duration-300"
    ></label>
    <span
        class="pointer-events-none absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow transform transition-transform duration-300 peer-checked:translate-x-6"
    ></span>
</div>
<style>
    .toggle-checkbox:checked + .toggle-label {
        background-color: #68D391;
    }
</style>
