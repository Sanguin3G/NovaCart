{{-- resources/views/admin/categories/index.blade.php --}}
<x-layouts.app :title="__('Categories')">
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <select id="parent-filter" class="border rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600">
                <option value="all">{{ __('All Parents') }}</option>
                <option value="none">{{ __('No Parent') }}</option>
                @foreach($parentCategories as $id => $name)
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

        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ __('Create Category') }}
        </a>
    </div>
    <style>
        .dataTables_length {
            margin-bottom: 1rem;
        }
    </style>

    <div class="overflow-x-auto">
        <table id="categories-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">{{ __('Name') }}</th>
                    <th class="px-4 py-2">{{ __('Parent') }}</th>
                    <th class="px-4 py-2">{{ __('Products Count') }}</th>
                    <th class="px-4 py-2">{{ __('Status') }}</th>
                    <th class="px-4 py-2">{{ __('Created At') }}</th>
                    <th class="px-4 py-2">{{ __('Updated At') }}</th>
                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- DataTables scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            const table = $('#categories-table').DataTable({
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.categories.data") }}',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: function (d) {
                        d.parent_filter = $('#parent-filter').val();
                        d.status_filter = $('#status-filter').val();
                        d.search_value = $('#search-input').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'parent_name', name: 'parent_name', orderable: false, searchable: false },
                    { data: 'products_count', name: 'products_count', orderable: false, searchable: false },
                    { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                    { data: 'formatted_created_at', name: 'formatted_created_at', orderable: false, searchable: false },
                    { data: 'formatted_updated_at', name: 'formatted_updated_at', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });

            $('#parent-filter, #status-filter').change(function () {
                table.draw();
            });

            $('#search-input').on('keyup', function () {
                table.draw();
            });
        });
    </script>
</x-layouts.app> 