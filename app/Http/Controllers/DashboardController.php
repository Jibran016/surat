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
        if ($user->role !== 'Admin') {
            return redirect()->route('dashboard.chart');
        }

        $masukCount = Surat::query()
            ->where('status', 'Terkirim')
            ->count();

        $keluarCount = Surat::query()
            ->where('status', '!=', 'Terkirim')
            ->count();

        $arsipCount = Surat::query()
            ->whereNotNull('archived_at')
            ->count();

        $notifCount = Notification::query()
            ->whereNull('read_at')
            ->count();

        $accountDivisionRows = User::query()
            ->orderByRaw("CASE WHEN role = 'Admin' THEN 0 ELSE 1 END")
            ->orderBy('division')
            ->orderBy('username')
            ->get(['id', 'username', 'email', 'role', 'division', 'created_at']);

        return view('dashboard-admin', compact(
            'masukCount',
            'keluarCount',
            'arsipCount',
            'notifCount',
            'accountDivisionRows'
        ));
    }

    public function chart(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'Admin') {
            return redirect()->route('dashboard');
        }

        $userDivision = trim((string) $user->division);
        $chartMode = $this->sanitizeChartMode($request->query('mode', 'bulanan'));
        ['labels' => $chartLabels, 'masuk' => $chartMasukData, 'keluar' => $chartKeluarData] = $this->buildChartData($userDivision, $chartMode);
        $summaryMasuk = Surat::query()
            ->when(
                $userDivision !== '',
                fn ($query) => $query->where('recipient_division', $userDivision),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->whereNull('archived_at')
            ->count();
        $summaryKeluar = Surat::query()
            ->when(
                $userDivision !== '',
                fn ($query) => $query->where('sender_division', $userDivision),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->whereNull('archived_at')
            ->count();
        $summaryTotal = $summaryMasuk + $summaryKeluar;
        $inboxPreview = Surat::query()
            ->when(
                $userDivision !== '',
                fn ($query) => $query->where('recipient_division', $userDivision),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->whereNull('archived_at')
            ->with(['sender:id,username,division'])
            ->orderByDesc('sent_at')
            ->limit(10)
            ->get();

        return view('dashboard-chart', compact(
            'chartMode',
            'chartLabels',
            'chartMasukData',
            'chartKeluarData',
            'summaryMasuk',
            'summaryKeluar',
            'summaryTotal',
            'inboxPreview'
        ));
    }

    public function monitoring()
    {
        $user = Auth::user();
        if ($user->role !== 'Admin') {
            return redirect()->route('dashboard');
        }

        $divisionMonitoring = $this->buildDivisionMonitoring();
        $totalSuratCount = Surat::count();

        return view('admin-monitoring', compact('divisionMonitoring', 'totalSuratCount'));
    }

    private function sanitizeChartMode(string $mode): string
    {
        return in_array($mode, ['mingguan', 'bulanan'], true) ? $mode : 'bulanan';
    }

    private function buildChartData(string $userDivision, string $mode): array
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
                $masuk[] = Surat::when(
                    $userDivision !== '',
                    fn ($query) => $query->where('recipient_division', $userDivision),
                    fn ($query) => $query->whereRaw('1 = 0')
                )
                    ->whereNull('archived_at')
                    ->whereBetween('sent_at', [$start, $end])
                    ->count();
                $keluar[] = Surat::when(
                    $userDivision !== '',
                    fn ($query) => $query->where('sender_division', $userDivision),
                    fn ($query) => $query->whereRaw('1 = 0')
                )
                    ->whereNull('archived_at')
                    ->whereBetween('sent_at', [$start, $end])
                    ->count();
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $start = $now->copy()->subMonths($i)->startOfMonth();
                $end = (clone $start)->endOfMonth();

                $labels[] = $start->format('M y');
                $masuk[] = Surat::when(
                    $userDivision !== '',
                    fn ($query) => $query->where('recipient_division', $userDivision),
                    fn ($query) => $query->whereRaw('1 = 0')
                )
                    ->whereNull('archived_at')
                    ->whereBetween('sent_at', [$start, $end])
                    ->count();
                $keluar[] = Surat::when(
                    $userDivision !== '',
                    fn ($query) => $query->where('sender_division', $userDivision),
                    fn ($query) => $query->whereRaw('1 = 0')
                )
                    ->whereNull('archived_at')
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
            ->selectRaw('recipient_division as division_name, COUNT(*) as total')
            ->groupBy('recipient_division')
            ->pluck('total', 'division_name');

        $outgoingByDivision = Surat::query()
            ->selectRaw('sender_division as division_name, COUNT(*) as total')
            ->groupBy('sender_division')
            ->pluck('total', 'division_name');

        $divisionNames = Division::query()
            ->orderBy('name')
            ->pluck('name')
            ->filter()
            ->values();

        $incomingNames = $incomingByDivision->keys()
            ->filter(fn ($name) => is_string($name) && trim($name) !== '')
            ->values();
        $outgoingNames = $outgoingByDivision->keys()
            ->filter(fn ($name) => is_string($name) && trim($name) !== '')
            ->values();
        $unknownIncoming = (int) ($incomingByDivision[null] ?? $incomingByDivision[''] ?? 0);
        $unknownOutgoing = (int) ($outgoingByDivision[null] ?? $outgoingByDivision[''] ?? 0);

        $allDivisionNames = $divisionNames
            ->merge($incomingNames)
            ->merge($outgoingNames)
            ->unique()
            ->sort()
            ->values();

        $monitoringRows = $allDivisionNames->map(function (string $divisionName) use ($incomingByDivision, $outgoingByDivision) {
            $masuk = (int) ($incomingByDivision[$divisionName] ?? 0);
            $keluar = (int) ($outgoingByDivision[$divisionName] ?? 0);

            return (object) [
                'name' => $divisionName,
                'masuk_count' => $masuk,
                'keluar_count' => $keluar,
                'total_count' => $masuk + $keluar,
            ];
        });

        if ($unknownIncoming > 0 || $unknownOutgoing > 0) {
            $monitoringRows->push((object) [
                'name' => 'Tanpa Divisi',
                'masuk_count' => $unknownIncoming,
                'keluar_count' => $unknownOutgoing,
                'total_count' => $unknownIncoming + $unknownOutgoing,
            ]);
        }

        return $monitoringRows;
    }

}
