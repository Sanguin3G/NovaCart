<x-layouts.app :title="__('Dashboard')">
    <div class="nc-page">
        <header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Platform') }}</p><h1 class="nc-title">{{ __('Dashboard') }}</h1><p class="nc-subtitle">{{ __('A calm overview of the store and the work that needs attention.') }}</p></div><a href="{{ route('admin.users.index') }}" class="nc-btn-secondary"><x-icon name="user" width="17" height="17"/>{{ __('Manage customers') }}</a></header>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach([
                ['label' => __('Customers'), 'value' => $users, 'icon' => 'user', 'tone' => 'text-blue-500'],
                ['label' => __('Categories'), 'value' => $totalCategories, 'icon' => 'tag', 'tone' => 'text-emerald-500'],
                ['label' => __('Active categories'), 'value' => $activeCategories, 'icon' => 'check', 'tone' => 'text-amber-500'],
                ['label' => __('Products'), 'value' => $totalProducts, 'icon' => 'package', 'tone' => 'text-purple-500'],
            ] as $metric)
                <div class="nc-card flex items-center gap-4 p-5"><div class="grid h-11 w-11 place-items-center rounded-xl bg-gray-100 dark:bg-gray-800"><x-icon name="{{ $metric['icon'] }}" class="{{ $metric['tone'] }}" width="23" height="23"/></div><div><p class="text-sm text-gray-500 dark:text-gray-400">{{ $metric['label'] }}</p><p class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">{{ $metric['value'] }}</p></div></div>
            @endforeach
        </div>
        <div class="nc-card nc-card-body"><h2 class="mb-5 text-lg font-semibold text-gray-950 dark:text-white">{{ __('Top categories by products') }}</h2><div class="h-72"><canvas id="categoryChart"></canvas></div>
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
