<x-layouts.app :title="__('My Reviews')">
    <div class="max-w-6xl mx-auto py-8">
        <h2 class="text-2xl font-semibold mb-6">{{ __('Reviews') }}</h2>

        <!-- Tabs -->
        <div class="mb-4 border-b border-gray-200 dark:border-gray-600">
            <nav class="flex space-x-8" id="reviews-tabs" aria-label="Tabs">
                <button data-tab="pending" class="tab-link text-orange-600 dark:text-orange-400 px-3 py-2 font-medium text-sm border-b-2 border-orange-500">{{ __('Pending') }}</button>
                <button data-tab="mine" class="tab-link text-gray-600 dark:text-gray-300 px-3 py-2 font-medium text-sm border-b-2 border-transparent hover:border-orange-500">{{ __('My Reviews') }}</button>
            </nav>
        </div>

        <!-- Pending Table -->
        <div id="tab-pending" class="tab-pane">
            <!-- built-in DataTables search will be used -->
            <table id="pending-table" class="min-w-full divide-y divide-gray-200 w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">{{ __('Image') }}</th>
                        <th class="px-4 py-2">{{ __('Product') }}</th>
                        <th class="px-4 py-2">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- My Reviews Table -->
        <div id="tab-mine" class="tab-pane hidden">
            <!-- built-in DataTables search will be used -->
            <table id="mine-table" class="min-w-full divide-y divide-gray-200 w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">{{ __('Image') }}</th>
                        <th class="px-4 py-2">{{ __('Product') }}</th>
                        <th class="px-4 py-2">{{ __('Rating') }}</th>
                        <th class="px-4 py-2">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Review Modal (re-using) -->
    <x-modal id="review-modal" closable="true">
        <div class="bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 p-4 rounded-t-lg text-white">
            <h3 id="review-modal-title" class="text-lg font-semibold"></h3>
        </div>
        <form id="review-form" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="rating" id="review-rating" value="0">
            <input type="hidden" id="product-id-hidden" value="">
            <div id="star-widget" class="flex items-center space-x-1 cursor-pointer text-gray-300 dark:text-gray-600">
                @for($i=1;$i<=5;$i++)
                    <svg data-value="{{ $i }}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 star" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.2 3.677a1 1 0 00.95.69h3.862c.969 0 1.371 1.24.588 1.81l-3.124 2.27a1 1 0 00-.364 1.118l1.2 3.678c.3.922-.755 1.688-1.54 1.118L10 13.348l-3.124 2.27c-.785.57-1.84-.196-1.54-1.118l1.2-3.678a1 1 0 00-.364-1.118L3.048 9.104c-.783-.57-.38-1.81.588-1.81h3.862a1 1 0 00.95-.69l1.2-3.677z" />
                    </svg>
                @endfor
            </div>
            <div>
                <label for="review-body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Your review') }}</label>
                <textarea id="review-body" name="body" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
            <div class="text-right">
                <x-button type="submit" variant="primary">{{ __('Submit') }}</x-button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        @vite('resources/js/customer/reviews-page.js')
    @endpush

    @push('styles')
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25em 0.75em;
            margin: 0 0.25em;
            border-radius: 0.25rem;
            border: 1px solid #d1d5db;
            background-color: white;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f97316;
            color: white !important;
            border-color: #f97316;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f97316;
            color: white !important;
            border-color: #f97316;
        }
        .dataTables_wrapper .dataTables_info {
             padding-top: 0.85em;
             color: #6b7280;
        }
        .dark .dataTables_wrapper .dataTables_info {
            color: #9ca3af;
        }
        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        #pending-table tbody tr:nth-child(even),
        #mine-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .dark #pending-table tbody tr:nth-child(even),
        .dark #mine-table tbody tr:nth-child(even) {
            background-color: #1f2937;
        }
        #pending-table tbody tr:hover,
        #mine-table tbody tr:hover {
            background-color: #fef3c7;
        }
        .dark #pending-table tbody tr:hover,
        .dark #mine-table tbody tr:hover {
            background-color: #374151;
        }

        /* DataTables built-in search styling */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1.25rem; /* more space before table */
        }
        .dataTables_wrapper .dataTables_filter label {
            font-weight: 500;
            color: #4b5563; /* gray-600 */
        }
        .dark .dataTables_wrapper .dataTables_filter label {
            color: #d1d5db; /* gray-300 */
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            margin-left: 0.5rem;
            outline: none;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #f97316; /* orange-500 */
        }
        .dark .dataTables_wrapper .dataTables_filter input {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        .dark .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #f97316;
        }
    </style>
    @endpush
</x-layouts.app> 