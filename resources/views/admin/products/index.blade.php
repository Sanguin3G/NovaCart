<x-layouts.app :title="__('Products')">
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <select id="category-filter" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                <option value="all">{{ __('All Categories') }}</option>
                @foreach($categories as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

            <select id="status-filter" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                <option value="all">{{ __('All Status') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
            </select>

            <input type="text" id="search-input" placeholder="{{ __('Search...') }}" class="border rounded px-2 py-1 w-full md:w-96 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" />
        </div>

        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ __('Create Product') }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="products-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">{{ __('Name') }}</th>
                    <th class="px-4 py-2">{{ __('Category') }}</th>
                    <th class="px-4 py-2">{{ __('Price') }}</th>
                    <th class="px-4 py-2">{{ __('Stock') }}</th>
                    <th class="px-4 py-2">{{ __('Status') }}</th>
                    <th class="px-4 py-2">{{ __('Created At') }}</th>
                    <th class="px-4 py-2">{{ __('Updated At') }}</th>
                    <th class="px-4 py-2">{{ __('Orders') }}</th>
                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    @vite('resources/js/admin/orders-modal.js')
    <script>
        $(document).ready(function () {
            const table = $('#products-table').DataTable({
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.products.data") }}',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: function (d) {
                        d.category_id = $('#category-filter').val();
                        d.status_filter = $('#status-filter').val();
                        d.search_value = $('#search-input').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'category_name', name: 'category.name' },
                    { data: 'price', name: 'price' },
                    { data: 'stock', name: 'stock' },
                    { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                    { data: 'formatted_created_at', name: 'formatted_created_at', orderable: false, searchable: false },
                    { data: 'formatted_updated_at', name: 'formatted_updated_at', orderable: false, searchable: false },
                    {
                        data: null,
                        name: 'orders',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            const safeName = String(row.name).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                            return `<button class="view-orders-btn text-blue-600 hover:underline" data-product-id="${row.id}" data-product-name="${safeName}">{{ __('View Orders') }}</button>`;
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });

            $('#category-filter, #status-filter').change(function () {
                table.draw();
            });

            $('#search-input').on('keyup', function () {
                table.draw();
            });
        });
    </script>
    @endpush
    <style>
        .dataTables_length {
            margin-bottom: 1rem;
        }
    </style>

    <!-- Orders Modal -->
    <x-modal id="orders-modal" closable="true">
        <h2 id="orders-modal-title" class="text-lg font-semibold p-4 border-b"></h2>
        <div class="p-4 space-y-4">
            <div class="flex items-center space-x-4">
                <select id="orders-status-filter" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                    <option value="all">{{ __('All Status') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="processing">{{ __('Processing') }}</option>
                    <option value="shipped">{{ __('Shipped') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                </select>
                <input type="text" id="orders-search-input" placeholder="{{ __('Search order #') }}" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
            </div>
            <div class="overflow-x-auto">
                <table id="product-orders-table" class="min-w-full divide-y divide-gray-200 w-full text-sm">
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
        </div>
    </x-modal>
</x-layouts.app> 