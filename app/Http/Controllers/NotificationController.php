<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $filter = $request->query('status', 'all');
        if (!in_array($filter, ['all', 'read', 'unread'], true)) {
            $filter = 'all';
        }

        $query = Notification::where('recipient_division', $user->division)
            ->orderByDesc('created_at');

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(10)->withQueryString();

        return view('notifications.index', compact('notifications', 'filter'));
    }

    public function open(Notification $notification)
    {
        $user = Auth::user();
        if ($user->role !== 'Admin' && $notification->recipient_division !== $user->division) {
            abort(403);
        }

        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }

        if ($notification->surat_id) {
            $surat = $notification->surat;
            if ($surat && $surat->recipient_division === $user->division && $surat->archived_at === null) {
                $surat->update([
                    'status' => $surat->status === 'Terkirim' ? 'Dibaca' : $surat->status,
                    'read_at' => $surat->read_at ?? now(),
                    'archived_at' => now(),
                ]);
            }
        }

        return redirect()->route('surat.show', $notification->surat_id);
    }
}
