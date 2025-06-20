<?php

namespace App\Providers;

use App\View\Composers\CartComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'customer.*',
            'components.layouts.app.*',
            'components.layouts.app.sidebar',
            'components.layouts.app.header'
        ], CartComposer::class);

        // Allow route-model binding for ProductReview to include soft-deleted records
        Route::bind('review', function ($value) {
            return \App\Models\ProductReview::withTrashed()->findOrFail($value);
        });
    }
}
