<?php

namespace App\Providers;

use App\Models\Booking;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.admin', function ($view) {
            $user = auth()->user();
            $quotations = collect();

            if ($user && $user->hasModulePermission('booking', 'view')) {
                $quotations = Booking::with('customer')
                    ->where('status', Booking::STATUS_QUOTATION)
                    ->orderBy('event_date')
                    ->orderBy('id')
                    ->get();
            }

            $view->with('quotationNotifications', $quotations);
        });

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
