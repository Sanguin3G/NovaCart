<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        // Register OAuth routes provided by Passport
        Passport::routes();

        // You can define additional gates here if needed
        Gate::define('admin', static fn($user) => $user instanceof Admin);
    }
}
