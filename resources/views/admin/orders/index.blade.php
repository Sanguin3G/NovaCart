<x-layouts.app :title="__('Orders')">
    <div class="mb-4 flex items-center space-x-4">
        <select id="status-filter" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
            <option value="all">{{ __('All Status') }}</option>
            <option value="pending">{{ __('Pending') }}</option>
            <option value="processing">{{ __('Processing') }}</option>
            <option value="shipped">{{ __('Shipped') }}</option>
            <option value="completed">{{ __('Completed') }}</option>
            <option value="cancelled">{{ __('Cancelled') }}</option>
        </select>
        <input type="text" id="search-input" placeholder="{{ __('Search order #') }}" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" />
    </div>

    <div class="overflow-x-auto">
        <table id="orders-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">{{ __('Order #') }}</th>
                    <th class="px-4 py-2">{{ __('Customer') }}</th>
                    <th class="px-4 py-2">{{ __('Items') }}</th>
                    <th class="px-4 py-2">{{ __('Total') }}</th>
                    <th class="px-4 py-2">{{ __('Status') }}</th>
                    <th class="px-4 py-2">{{ __('Date') }}</th>
                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script>
            $(function(){
                const table = $('#orders-table').DataTable({
                    dom: 'lrtip',
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route("admin.orders.data") }}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: function(d){
                            d.status_filter = $('#status-filter').val();
                            d.search_value = $('#search-input').val();
                        }
                    },
                    columns: [
                        {data:'DT_RowIndex', orderable:false, searchable:false},
                        {data:'order_number'},
                        {data:'customer'},
                        {data:'items_count'},
                        {data:'total_amount'},
                        {data:'status'},
                        {data:'created_at'},
                        {data:'actions', orderable:false, searchable:false},
                    ],
                });

                $('#status-filter').on('change', ()=>table.draw());
                $('#search-input').on('keyup', ()=>table.draw());
            });
        </script>
    @endpush
</x-layouts.app>
