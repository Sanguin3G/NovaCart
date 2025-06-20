<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-gradient-to-r from-green-100 via-amber-50 to-yellow-100 dark:from-green-900 dark:via-amber-900/20 dark:to-yellow-900/20 shadow-lg sm:rounded-xl mb-10 border border-green-200 dark:border-green-700 flex flex-col sm:flex-row items-center gap-6 px-6 py-10">
                <div
                    class="flex-shrink-0 p-4 rounded-full bg-green-200 dark:bg-green-800 text-green-700 dark:text-green-300 shadow mb-4 sm:mb-0">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-2.21 0-4 1.79-4 4v2h8v-2c0-2.21-1.79-4-4-4z"/>
                    </svg>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <div class="text-2xl font-bold text-green-900 dark:text-green-200 mb-2">Welcome
                        back, {{ Auth::user()?->name }}!
                    </div>
                    <div class="text-base text-green-700 dark:text-green-300">Here are your recent orders.</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8 flex items-center">
                    <div class="p-4 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-6">
                        <x-heroicon-o-shopping-bag class="h-8 w-8"/>
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-500 dark:text-zinc-400">My Total Orders</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $customerTotalOrders }}</h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                <a href="{{ route('products.index') }}"
                   class="flex items-center p-6 bg-teal-50 dark:bg-teal-900/20 rounded-lg hover:bg-teal-100 dark:hover:bg-teal-900/30 transition-colors border border-teal-200 dark:border-teal-800 shadow-sm">
                    <div class="p-3 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 mr-4">
                        <x-heroicon-o-squares-2x2 class="h-6 w-6"/>
                    </div>
                    <span class="text-base font-medium text-teal-900 dark:text-teal-200">Browse Products</span>
                </a>
                <a href="{{ route('orders.index') }}"
                   class="flex items-center p-6 bg-gray-50 dark:bg-gray-900/20 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900/30 transition-colors border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="p-3 rounded-full bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-300 mr-4">
                        <x-heroicon-o-document-text class="h-6 w-6"/>
                    </div>
                    <span class="text-base font-medium text-gray-900 dark:text-gray-100">My Orders</span>
                </a>
                <a href="{{ route('settings.profile.edit') }}"
                   class="flex items-center p-6 bg-amber-50 dark:bg-amber-900/20 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors border border-amber-200 dark:border-amber-800 shadow-sm">
                    <div
                        class="p-3 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mr-4">
                        <x-icon name="heroicon-o-user" class="h-6 w-6"/>
                    </div>
                    <span class="text-base font-medium text-amber-900 dark:text-amber-200">Account Settings</span>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">My Orders</h2>
                        <div class="flex items-center space-x-4">
                            <input type="text" id="search-input" placeholder="{{ __('Search by Order #...') }}"
                                   class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-orange-500 focus:border-orange-500"/>
                            <select id="status-filter"
                                    class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:ring-orange-500 focus:border-orange-500">
                                <option value="all">{{ __('All Statuses') }}</option>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="processing">{{ __('Processing') }}</option>
                                <option value="shipped">{{ __('Shipped') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="p-6 sm:px-10">
                    <div class="overflow-x-auto">
                        <table id="orders-table" class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Order #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                const table = $('#orders-table').DataTable({
                    dom: 'rt<"bottom"ip><"clear">',
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route("orders.data") }}',
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: function (d) {
                            d.status_filter = $('#status-filter').val();
                            d.search_value = $('#search-input').val();
                        }
                    },
                    autoWidth: false,
                    scrollX: true,
                    columns: [
                        {data: 'order_number', name: 'order_number'},
                        {data: 'created_at', name: 'created_at', className: 'whitespace-nowrap'},
                        {data: 'status', name: 'status', className: 'text-center'},
                        {data: 'total_amount', name: 'total_amount', className: 'text-right'},
                    ],
                    order: [[1, 'desc']],
                    language: {
                        processing: `
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="dark:text-white">Processing...</span>
                        </div>
                    `,
                        emptyTable: "No orders found.",
                        zeroRecords: "No matching orders found.",
                    }
                });

                $('#status-filter').change(function () {
                    table.draw();
                });

                $('#search-input').on('keyup', function () {
                    table.draw();
                });
            });
        </script>
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
        </style>
    @endpush
</x-layouts.app>
