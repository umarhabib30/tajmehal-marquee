<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::if('superAdmin', function () {
            return auth()->check() && auth()->user()->isSuperAdmin();
        });

        Blade::if('modulePerm', function (string $module, string $action) {
            return auth()->check() && auth()->user()->hasModulePermission($module, $action);
        });

        Blade::if('moduleNav', function (string $module) {
            return auth()->check() && auth()->user()->canAccessModuleNav($module);
        });
    }
}
