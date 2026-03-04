<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
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
        View::composer('*', function ($view) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();
            $notifCount = Notification::where('recipient_division', $user->division)
                ->whereNull('read_at')
                ->count();

            $view->with('globalNotifCount', $notifCount);
        });
    }
}
