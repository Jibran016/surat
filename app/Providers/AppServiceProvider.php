<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\Surat;
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
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));

        View::composer('*', function ($view) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();
            $notifQuery = Notification::where('recipient_division', $user->division)
                ->orderByDesc('created_at');

            $notifCount = (clone $notifQuery)
                ->whereNull('read_at')
                ->count();

            $notifItems = (clone $notifQuery)
                ->limit(8)
                ->get();

            $view->with('globalNotifCount', $notifCount);
            $view->with('globalNotifItems', $notifItems);

            if ($user->role !== 'Admin') {
                $sidebarMasukCount = Surat::where('recipient_division', $user->division)
                    ->whereNull('archived_at')
                    ->count();

                $sidebarKeluarCount = Surat::where('sender_user_id', $user->id)
                    ->whereNull('archived_at')
                    ->where('status', 'Terkirim')
                    ->count();

                $sidebarArsipCount = Surat::where('recipient_division', $user->division)
                    ->whereNotNull('archived_at')
                    ->count();

                $view->with('sidebarMasukCount', $sidebarMasukCount);
                $view->with('sidebarKeluarCount', $sidebarKeluarCount);
                $view->with('sidebarArsipCount', $sidebarArsipCount);
            }
        });
    }
}
