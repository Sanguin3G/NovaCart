<x-layouts.app :title="__('Product Reviews')">
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <select id="status-filter" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                <option value="all">{{ __('All Status') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="disabled">{{ __('Disabled') }}</option>
            </select>
            <input type="text" id="search-input" placeholder="{{ __('Search…') }}" class="border rounded px-2 py-1 w-72 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="reviews-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">{{ __('Product') }}</th>
                    <th class="px-4 py-2">{{ __('Reviewer') }}</th>
                    <th class="px-4 py-2">{{ __('Rating') }}</th>
                    <th class="px-4 py-2 cursor-pointer">{{ __('Status') }}</th>
                    <th class="px-4 py-2">{{ __('Created') }}</th>
                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Review Detail Modal -->
    <dialog id="review-detail-modal" class="rounded-lg p-0 w-full max-w-xl shadow-lg backdrop:bg-black/50"></dialog>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        @vite('resources/js/admin/reviews.js')
    @endpush
</x-layouts.app> 