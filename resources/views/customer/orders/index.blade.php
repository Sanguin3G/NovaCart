<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">All My Orders</h2>
                        <div class="flex items-center space-x-4">
                             <input type="text" id="search-input" placeholder="{{ __('Search by Order #...') }}" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-orange-500 focus:border-orange-500" />
                            <select id="status-filter" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:ring-orange-500 focus:border-orange-500">
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Order #</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Items</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Payment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Shipping Address</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
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
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: function (d) {
                        d.status_filter = $('#status-filter').val();
                        d.search_value = $('#search-input').val();
                    }
                },
                autoWidth: false,
                scrollX: true,
                columns: [
                    { data: 'order_number', name: 'order_number' },
                    { data: 'created_at', name: 'created_at', className: 'whitespace-nowrap' },
                    { data: 'items_count', name: 'order_items_count', className: 'text-center' },
                    { data: 'payment_method', name: 'payment_method' },
                    { data: 'shipping_address', name: 'shipping_address' },
                    { data: 'status', name: 'status' },
                    { data: 'total_amount', name: 'total_amount', className: 'text-right' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
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

            // Handle cancel order action using reusable helper
            $(document).on('click', '.cancel-order-btn', function () {
                const orderId = $(this).data('id');
                cancelOrder(orderId, () => table.draw(false));
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

        /* Fancier Table Styles */
        #orders-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .dark #orders-table tbody tr:nth-child(even) {
            background-color: #1f2937; /* A slightly lighter shade than the main dark bg */
        }
        #orders-table tbody tr:hover {
            background-color: #fef3c7;
        }
        .dark #orders-table tbody tr:hover {
            background-color: #374151;
        }

        .view-order-btn {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            background-color: #fb923c;
            color: white;
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            transition: background-color 0.2s;
            display: inline-block;
            text-align: center;
        }
        .view-order-btn:hover {
            background-color: #f97316;
            color: white;
        }

        .cancel-order-btn {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            background-color: #ef4444;
            color: white;
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            transition: background-color 0.2s;
            display: inline-block;
            text-align: center;
        }
        .cancel-order-btn:hover {
            background-color: #dc2626;
            color: white;
        }
    </style>
    @endpush
</x-layouts.app> 