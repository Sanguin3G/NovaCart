<x-layouts.app :title="__('Products To Review')">
    <div class="max-w-6xl mx-auto py-8">
        <h2 class="text-2xl font-semibold mb-4">{{ __('Products you have purchased but not reviewed yet') }}</h2>

        <div class="overflow-x-auto">
            <table id="todo-reviews-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">{{ __('Image') }}</th>
                        <th class="px-4 py-2">{{ __('Name') }}</th>
                        <th class="px-4 py-2">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        @vite('resources/js/customer/reviews.js')
    @endpush
</x-layouts.app> 