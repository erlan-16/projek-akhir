<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Payment;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share pending payments count to all views
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->isAdmin()) {
                $pendingPaymentsCount = Payment::where('status', 'pending')->count();
                $view->with('pendingPaymentsCount', $pendingPaymentsCount);
            }
        });
    }
}