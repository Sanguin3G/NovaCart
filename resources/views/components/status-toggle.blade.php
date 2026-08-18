@props(['url', 'checked' => false])

<div class="relative inline-block h-6 w-11">
    <input
        id="toggle-{{ md5($url) }}"
        type="checkbox"
        class="js-status-toggle peer absolute h-0 w-0 opacity-0"
        data-url="{{ $url }}"
        {{ $checked ? 'checked' : '' }}
    />
    <label
        for="toggle-{{ md5($url) }}"
        class="block h-6 w-11 cursor-pointer rounded-full bg-gray-300 transition-colors duration-300 peer-checked:bg-emerald-500 dark:bg-gray-700"
    ></label>
    <span
        class="pointer-events-none absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-300 peer-checked:translate-x-5"
    ></span>
</div>
<style>
    .toggle-checkbox:checked + .toggle-label {
        background-color: #68D391;
    }
</style>
