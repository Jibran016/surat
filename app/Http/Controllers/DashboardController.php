<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $masukCount = Surat::where('recipient_division', $user->division)
            ->whereNull('archived_at')
            ->count();

        $keluarCount = Surat::where('sender_user_id', $user->id)
            ->whereNull('archived_at')
            ->count();

        $arsipCount = Surat::where(function ($query) use ($user) {
            $query->where('sender_user_id', $user->id)
                ->orWhere('recipient_division', $user->division);
        })->whereNotNull('archived_at')->count();

        $notifCount = Notification::where('recipient_division', $user->division)
            ->whereNull('read_at')
            ->count();

        if ($user->role === 'Admin') {
            return view('dashboard-admin', compact('masukCount', 'keluarCount', 'arsipCount', 'notifCount'));
        }

        return view('dashboard-user', compact('masukCount', 'keluarCount', 'arsipCount', 'notifCount'));
    }
}
