<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Notification;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $chartMode = $this->sanitizeChartMode($request->query('mode', 'bulanan'));

        $masukCount = Surat::where('recipient_division', $user->division)
            ->whereNull('archived_at')
            ->count();

        $keluarCount = Surat::where('sender_division', $user->division)
            ->whereNull('archived_at')
            ->where('status', 'Terkirim')
            ->count();

        $arsipCount = Surat::where('recipient_division', $user->division)
            ->whereNotNull('archived_at')
            ->count();

        $notifCount = Notification::where('recipient_division', $user->division)
            ->whereNull('read_at')
            ->count();

        ['labels' => $chartLabels, 'masuk' => $chartMasukData, 'keluar' => $chartKeluarData] = $this->buildChartData($user, $chartMode);

        if ($user->role === 'Admin') {
            $divisionCodes = Division::query()
                ->pluck('unit_code', 'name');

            $accountDivisionRows = User::query()
                ->orderByRaw("CASE WHEN role = 'Admin' THEN 0 ELSE 1 END")
                ->orderBy('division')
                ->orderBy('username')
                ->get(['id', 'username', 'email', 'role', 'division', 'created_at'])
                ->map(function (User $row) use ($divisionCodes) {
                    $row->division_code = $divisionCodes[$row->division] ?? '-';

                    return $row;
                });

            return view('dashboard-admin', compact(
                'masukCount',
                'keluarCount',
                'arsipCount',
                'notifCount',
                'accountDivisionRows'
            ));
        }

        $inboxPreview = Surat::where('recipient_division', $user->division)
            ->whereNull('archived_at')
            ->orderByDesc('sent_at')
            ->limit(8)
            ->get();

        return view('dashboard-user', compact(
            'masukCount',
            'keluarCount',
            'arsipCount',
            'notifCount',
            'chartMode',
            'chartLabels',
            'chartMasukData',
            'chartKeluarData',
            'inboxPreview'
        ));
    }

    public function chart(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'Admin') {
            return redirect()->route('dashboard');
        }

        $chartMode = $this->sanitizeChartMode($request->query('mode', 'bulanan'));
        ['labels' => $chartLabels, 'masuk' => $chartMasukData, 'keluar' => $chartKeluarData] = $this->buildChartData($user, $chartMode);

        return view('dashboard-chart', compact('chartMode', 'chartLabels', 'chartMasukData', 'chartKeluarData'));
    }

    public function monitoring()
    {
        $user = Auth::user();
        if ($user->role !== 'Admin') {
            return redirect()->route('dashboard');
        }

        $divisionMonitoring = $this->buildDivisionMonitoring();

        return view('admin-monitoring', compact('divisionMonitoring'));
    }

    private function sanitizeChartMode(string $mode): string
    {
        return in_array($mode, ['mingguan', 'bulanan'], true) ? $mode : 'bulanan';
    }

    private function buildChartData(User $user, string $mode): array
    {
        $labels = [];
        $masuk = [];
        $keluar = [];
        $now = now(config('app.timezone'));

        if ($mode === 'mingguan') {
            for ($i = 6; $i >= 0; $i--) {
                $start = $now->copy()->subDays($i)->startOfDay();
                $end = (clone $start)->endOfDay();

                $labels[] = $start->format('d M');
                $masuk[] = Surat::where('recipient_division', $user->division)
                    ->whereNull('archived_at')
                    ->whereBetween('sent_at', [$start, $end])
                    ->count();
                $keluar[] = Surat::where('sender_division', $user->division)
                    ->whereNull('archived_at')
                    ->where('status', 'Terkirim')
                    ->whereBetween('sent_at', [$start, $end])
                    ->count();
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $start = $now->copy()->subMonths($i)->startOfMonth();
                $end = (clone $start)->endOfMonth();

                $labels[] = $start->format('M y');
                $masuk[] = Surat::where('recipient_division', $user->division)
                    ->whereNull('archived_at')
                    ->whereBetween('sent_at', [$start, $end])
                    ->count();
                $keluar[] = Surat::where('sender_division', $user->division)
                    ->whereNull('archived_at')
                    ->where('status', 'Terkirim')
                    ->whereBetween('sent_at', [$start, $end])
                    ->count();
            }
        }

        return [
            'labels' => $labels,
            'masuk' => $masuk,
            'keluar' => $keluar,
        ];
    }

    private function buildDivisionMonitoring()
    {
        $incomingByDivision = Surat::query()
            ->whereNull('archived_at')
            ->selectRaw('recipient_division as division_name, COUNT(*) as total')
            ->groupBy('recipient_division')
            ->pluck('total', 'division_name');

        $outgoingByDivision = Surat::query()
            ->whereNull('archived_at')
            ->selectRaw('sender_division as division_name, COUNT(*) as total')
            ->groupBy('sender_division')
            ->pluck('total', 'division_name');

        return Division::query()
            ->orderBy('name')
            ->get(['name', 'unit_code'])
            ->map(function (Division $division) use ($incomingByDivision, $outgoingByDivision) {
                $masuk = (int) ($incomingByDivision[$division->name] ?? 0);
                $keluar = (int) ($outgoingByDivision[$division->name] ?? 0);

                return (object) [
                    'name' => $division->name,
                    'unit_code' => $division->unit_code,
                    'masuk_count' => $masuk,
                    'keluar_count' => $keluar,
                    'total_count' => $masuk + $keluar,
                ];
            });
    }

}
