<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with metrics and charts.
     */
    public function index(Request $request): View
    {
        // Metrics
        $users = User::count();
        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();

        // Top 10 categories by products count
        $top = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get();
        $chartLabels = $top->pluck('name')->toArray();
        $chartData = $top->pluck('products_count')->toArray();

        return view('admin.dashboard', compact(
            'users',
            'totalCategories',
            'activeCategories',
            'totalProducts',
            'activeProducts',
            'chartLabels',
            'chartData'
        ));
    }
}
