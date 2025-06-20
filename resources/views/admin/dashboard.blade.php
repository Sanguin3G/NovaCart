<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto p-6 space-y-6">
        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Users -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center">
                <div class="p-3 bg-blue-500 rounded-full text-white">
                    <x-phosphor-user-circle aria-hidden="true" width="24" height="24"/>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Users') }}</p>
                    <p class="text-2xl font-semibold">{{ $users }}</p>
                </div>
            </div>
            <!-- Total Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center">
                <div class="p-3 bg-green-500 rounded-full text-white">
                    <x-phosphor-tag aria-hidden="true" width="24" height="24"/>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Categories') }}</p>
                    <p class="text-2xl font-semibold">{{ $totalCategories }}</p>
                </div>
            </div>
            <!-- Active Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center">
                <div class="p-3 bg-yellow-500 rounded-full text-white">
                    <x-phosphor-check-circle aria-hidden="true" width="24" height="24"/>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Active Categories') }}</p>
                    <p class="text-2xl font-semibold">{{ $activeCategories }}</p>
                </div>
            </div>
            <!-- Total Products -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex items-center">
                <div class="p-3 bg-purple-500 rounded-full text-white">
                    <x-phosphor-package aria-hidden="true" width="24" height="24"/>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Products') }}</p>
                    <p class="text-2xl font-semibold">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <!-- Chart: Top Categories -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('Top Categories by Products') }}</h3>
            <canvas id="categoryChart" height="100"></canvas>
        </div>
    </div>

    <!-- Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: '{{ __('Products') }}',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(95, 178, 231, 0.6)',
                        borderColor: 'rgba(95, 178, 231, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.app>
