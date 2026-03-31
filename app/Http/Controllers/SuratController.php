<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Notification;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuratController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $divisions = Division::query()
            ->when(!empty($user->division), function ($query) use ($user) {
                $query->where('name', '!=', $user->division);
            })
            ->orderBy('name')
            ->pluck('name');

        $jenisList = $this->jenisList();

        return view('surat.create', compact('user', 'divisions', 'jenisList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'jenis' => ['nullable', 'string'],
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['nullable', 'string'],
            'lampiran' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'recipient_divisions' => ['required', 'array', 'min:1'],
            'recipient_divisions.*' => [
                'required',
                'string',
                'exists:divisions,name',
                Rule::notIn(array_filter([$user->division])),
            ],
        ]);

        $lampiranPath = null;
        $lampiranName = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('attachments', 'public');
            $lampiranName = $request->file('lampiran')->getClientOriginalName();
        }

        $recipients = collect($data['recipient_divisions'])->filter()->unique()->values();

        $now = now(config('app.timezone'));
        $sentAt = $now->copy();
        $sequence = $this->nextSequence($user->division, $now->format('Y'));
        $lastSurat = null;
        $jenis = trim((string) ($data['jenis'] ?? ''));
        $jenis = $jenis !== '' ? $jenis : 'Surat Pengantar';
        $isiPesan = trim((string) ($data['isi'] ?? ''));
        $isiHtml = $isiPesan !== ''
            ? nl2br(e($isiPesan))
            : '<p>Pesan dikirim dengan lampiran oleh divisi ' . e($user->division) . '.</p>';

        foreach ($recipients as $recipientDivision) {
            $nomorSurat = $this->buildNomorSurat($user->division, $sequence, $sentAt);
            $sequence++;

            $surat = Surat::create([
                'parent_id' => null,
                'sender_user_id' => $user->id,
                'sender_division' => $user->division,
                'recipient_division' => $recipientDivision,
                'cc_divisions' => [],
                'tembusan_list' => [$recipientDivision],
                'nomor_surat' => $nomorSurat,
                'jenis' => $jenis,
                'template_name' => null,
                'judul' => $data['judul'],
                'isi' => $isiHtml,
                'lampiran_path' => $lampiranPath,
                'lampiran_name' => $lampiranName,
                'status' => 'Terkirim',
                'sent_at' => $sentAt,
            ]);

            Notification::create([
                'surat_id' => $surat->id,
                'recipient_division' => $surat->recipient_division,
                'message' => 'Surat baru dari divisi ' . $surat->sender_division,
            ]);

            $lastSurat = $surat;
        }

        $statusMessage = 'Surat terkirim ke ' . $recipients->count() . ' divisi.';

        return redirect()
            ->route('surat.outbox')
            ->with('status', $statusMessage);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $division = $user->division;
        $search = trim((string) $request->query('q', ''));
        $tipe = $request->query('tipe', 'all');
        if (!in_array($tipe, ['all', 'masuk', 'keluar'], true)) {
            $tipe = 'all';
        }

        $incomingQuery = Surat::query()
            ->where(function ($query) use ($division) {
                $query->where('recipient_division', $division)
                    ->orWhereJsonContains('cc_divisions', $division);
            })
            ->whereNull('archived_at');

        $outgoingQuery = Surat::query()
            ->where('sender_user_id', $user->id)
            ->whereNull('archived_at')
            ->where('status', 'Terkirim');

        $incomingCount = (clone $incomingQuery)->count();
        $outgoingCount = (clone $outgoingQuery)->count();
        $totalCount = $incomingCount + $outgoingCount;

        $suratsQuery = Surat::query()
            ->with(['sender:id,username,division']);

        if ($tipe === 'masuk') {
            $suratsQuery->where(function ($query) use ($division) {
                $query->where('recipient_division', $division)
                    ->orWhereJsonContains('cc_divisions', $division);
            })->whereNull('archived_at');
        } elseif ($tipe === 'keluar') {
            $suratsQuery->where('sender_user_id', $user->id)
                ->whereNull('archived_at')
                ->where('status', 'Terkirim');
        } else {
            $suratsQuery->where(function ($query) use ($division, $user) {
                $query->where(function ($inbox) use ($division) {
                    $inbox->where('recipient_division', $division)
                        ->orWhereJsonContains('cc_divisions', $division);
                })->orWhere(function ($outbox) use ($user) {
                    $outbox->where('sender_user_id', $user->id)
                        ->where('status', 'Terkirim');
                });
            })->whereNull('archived_at');
        }

        if ($search !== '') {
            $suratsQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('sender_division', 'like', '%' . $search . '%')
                    ->orWhere('recipient_division', 'like', '%' . $search . '%')
                    ->orWhere('jenis', 'like', '%' . $search . '%')
                    ->orWhere('nomor_surat', 'like', '%' . $search . '%');
            });
        }

        $surats = $suratsQuery
            ->orderByDesc('sent_at')
            ->paginate(10)
            ->withQueryString();

        return view('surat.index', compact(
            'surats',
            'search',
            'tipe',
            'totalCount',
            'incomingCount',
            'outgoingCount'
        ));
    }

    public function inbox(Request $request)
    {
        return redirect()->route('surat.index', array_merge($request->query(), ['tipe' => 'masuk']));
    }

    public function outbox(Request $request)
    {
        return redirect()->route('surat.index', array_merge($request->query(), ['tipe' => 'keluar']));
    }

    public function archiveIndex(Request $request)
    {
        $user = Auth::user();
        $division = $user->division;
        $tipe = $request->query('tipe', 'all');
        $search = trim((string) $request->query('q', ''));
        $date = trim((string) $request->query('date', ''));
        if (!in_array($tipe, ['all', 'masuk', 'keluar'], true)) {
            $tipe = 'all';
        }
        if ($date !== '') {
            try {
                $date = \Illuminate\Support\Carbon::parse($date)->format('Y-m-d');
            } catch (\Throwable $th) {
                $date = '';
            }
        }

        $baseQuery = Surat::query()
            ->whereNotNull('archived_at')
            ->where(function ($query) use ($division, $user) {
                $query->where('sender_user_id', $user->id)
                    ->orWhere('recipient_division', $division)
                    ->orWhereJsonContains('cc_divisions', $division);
            })
            ->when($date !== '', function ($query) use ($date) {
                $query->whereDate('sent_at', $date);
            });

        $totalArchiveCount = (clone $baseQuery)->count();
        $incomingArchiveCount = (clone $baseQuery)
            ->where('sender_user_id', '!=', $user->id)
            ->where(function ($nested) use ($division) {
                $nested->where('recipient_division', $division)
                    ->orWhereJsonContains('cc_divisions', $division);
            })
            ->count();
        $outgoingArchiveCount = (clone $baseQuery)
            ->where('sender_user_id', $user->id)
            ->count();
        $monthArchiveCount = (clone $baseQuery)
            ->whereYear('sent_at', now(config('app.timezone'))->year)
            ->whereMonth('sent_at', now(config('app.timezone'))->month)
            ->count();

        $suratsQuery = (clone $baseQuery)
            ->with(['sender:id,username']);

        if ($search !== '') {
            $suratsQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('sender_division', 'like', '%' . $search . '%')
                    ->orWhere('recipient_division', 'like', '%' . $search . '%')
                    ->orWhere('jenis', 'like', '%' . $search . '%')
                    ->orWhere('nomor_surat', 'like', '%' . $search . '%');
            });
        }

        $surats = $suratsQuery
            ->when($tipe === 'keluar', function ($query) use ($user) {
                $query->where('sender_user_id', $user->id);
            })
            ->when($tipe === 'masuk', function ($query) use ($division, $user) {
                $query->where('sender_user_id', '!=', $user->id)
                    ->where(function ($nested) use ($division) {
                        $nested->where('recipient_division', $division)
                            ->orWhereJsonContains('cc_divisions', $division);
                    });
            })
            ->orderByDesc('sent_at')
            ->get();

        if ($request->boolean('export')) {
            $format = strtolower((string) $request->query('format', 'csv'));
            if (!in_array($format, ['csv', 'xls'], true)) {
                $format = 'csv';
            }

            $filenameSuffix = $date !== '' ? $date : now(config('app.timezone'))->format('Y-m-d');
            $filename = 'arsip-surat-' . $filenameSuffix . '.' . $format;
            $headers = [
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            if ($format === 'xls') {
                $headers['Content-Type'] = 'application/vnd.ms-excel; charset=UTF-8';

                $callback = function () use ($surats, $user) {
                    $output = fopen('php://output', 'w');
                    fwrite($output, chr(239) . chr(187) . chr(191));
                    fwrite($output, implode("\t", ['Tanggal', 'Nomor', 'Judul', 'Dari', 'Kepada', 'Jenis', 'Tipe', 'Status']) . "\n");

                    foreach ($surats as $surat) {
                        $isOutgoing = $surat->sender_user_id === $user->id;
                        $row = [
                            optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '-',
                            $surat->nomor_surat ?? '-',
                            $surat->judul,
                            $surat->sender_division,
                            $surat->recipient_division,
                            $surat->jenis,
                            $isOutgoing ? 'Keluar' : 'Masuk',
                            $surat->status,
                        ];

                        $sanitized = array_map(function ($value) {
                            $text = (string) $value;
                            $text = str_replace(["\t", "\r", "\n"], ' ', $text);
                            return $text;
                        }, $row);

                        fwrite($output, implode("\t", $sanitized) . "\n");
                    }

                    fclose($output);
                };
            } else {
                $headers['Content-Type'] = 'text/csv; charset=UTF-8';

                $callback = function () use ($surats, $user) {
                    $output = fopen('php://output', 'w');
                    fputcsv($output, ['Tanggal', 'Nomor', 'Judul', 'Dari', 'Kepada', 'Jenis', 'Tipe', 'Status']);

                    foreach ($surats as $surat) {
                        $isOutgoing = $surat->sender_user_id === $user->id;
                        fputcsv($output, [
                            optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '-',
                            $surat->nomor_surat ?? '-',
                            $surat->judul,
                            $surat->sender_division,
                            $surat->recipient_division,
                            $surat->jenis,
                            $isOutgoing ? 'Keluar' : 'Masuk',
                            $surat->status,
                        ]);
                    }

                    fclose($output);
                };
            }

            return response()->stream($callback, 200, $headers);
        }

        return view('surat.archive', compact(
            'surats',
            'tipe',
            'search',
            'date',
            'totalArchiveCount',
            'incomingArchiveCount',
            'outgoingArchiveCount',
            'monthArchiveCount'
        ));
    }

    public function fileInventory(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'Admin') {
            return redirect()->route('dashboard');
        }

        $division = $user->division;
        $search = trim((string) $request->query('q', ''));

        $baseQuery = Surat::query()
            ->whereNotNull('lampiran_path')
            ->where(function ($query) use ($division, $user) {
                $query->where('sender_user_id', $user->id)
                    ->orWhere('recipient_division', $division)
                    ->orWhereJsonContains('cc_divisions', $division);
            });

        $totalFileCount = (clone $baseQuery)->count();
        $incomingFileCount = (clone $baseQuery)
            ->where('sender_user_id', '!=', $user->id)
            ->where(function ($nested) use ($division) {
                $nested->where('recipient_division', $division)
                    ->orWhereJsonContains('cc_divisions', $division);
            })
            ->count();
        $outgoingFileCount = (clone $baseQuery)
            ->where('sender_user_id', $user->id)
            ->count();

        $filesQuery = (clone $baseQuery);

        if ($search !== '') {
            $filesQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('nomor_surat', 'like', '%' . $search . '%')
                    ->orWhere('sender_division', 'like', '%' . $search . '%')
                    ->orWhere('recipient_division', 'like', '%' . $search . '%')
                    ->orWhere('jenis', 'like', '%' . $search . '%')
                    ->orWhere('lampiran_name', 'like', '%' . $search . '%');
            });
        }

        $files = $filesQuery
            ->orderByDesc('sent_at')
            ->paginate(10)
            ->withQueryString();

        return view('surat.inventory', compact(
            'files',
            'search',
            'totalFileCount',
            'incomingFileCount',
            'outgoingFileCount'
        ));
    }

    public function inventoryUploadForm()
    {
        $user = Auth::user();
        if ($user->role === 'Admin') {
            return redirect()->route('dashboard');
        }

        $jenisList = $this->jenisList();

        return view('surat.inventory-upload', compact('jenisList'));
    }

    public function storeInventory(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'Admin') {
            return redirect()->route('dashboard');
        }

        $jenisList = $this->jenisList();
        $data = $request->validate([
            'jenis' => ['required', 'string', Rule::in($jenisList)],
            'judul' => ['required', 'string', 'max:150'],
            'sent_at' => ['required', 'date'],
            'lampiran' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
        ]);

        $lampiranPath = $request->file('lampiran')->store('attachments', 'public');
        $lampiranName = $request->file('lampiran')->getClientOriginalName();
        $sentAt = Carbon::parse($data['sent_at'], config('app.timezone'));

        Surat::create([
            'parent_id' => null,
            'sender_user_id' => $user->id,
            'sender_division' => $user->division,
            'recipient_division' => $user->division,
            'cc_divisions' => [],
            'tembusan_list' => [],
            'nomor_surat' => null,
            'jenis' => $data['jenis'],
            'template_name' => null,
            'judul' => $data['judul'],
            'isi' => '<p>Dokumen surat lama yang diunggah melalui inventori file.</p>',
            'lampiran_path' => $lampiranPath,
            'lampiran_name' => $lampiranName,
            'status' => 'Selesai',
            'sent_at' => $sentAt,
            'read_at' => $sentAt,
            'archived_at' => now(config('app.timezone')),
        ]);

        return redirect()
            ->route('surat.inventory')
            ->with('status', 'Surat lama berhasil diunggah ke inventori.');
    }

    private function jenisList(): array
    {
        return [
            'Memorandum',
            'Surat Edaran',
            'Surat Undangan Rapat',
            'Surat Tugas',
            'Surat Keputusan',
            'Surat Pemberitahuan',
            'Surat Pengantar',
        ];
    }

    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'Admin') {
            return redirect()->route('dashboard');
        }

        $tipe = $request->query('tipe', 'masuk');
        $search = trim((string) $request->query('q', ''));

        if (!in_array($tipe, ['masuk', 'keluar'], true)) {
            $tipe = 'masuk';
        }

        $suratsQuery = Surat::query()
            ->when($tipe === 'masuk', function ($query) {
                $query->where('status', 'Terkirim');
            })
            ->when($tipe === 'keluar', function ($query) {
                $query->where('status', '!=', 'Terkirim');
            });

        if ($search !== '') {
            $suratsQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('nomor_surat', 'like', '%' . $search . '%')
                    ->orWhere('sender_division', 'like', '%' . $search . '%')
                    ->orWhere('recipient_division', 'like', '%' . $search . '%')
                    ->orWhere('jenis', 'like', '%' . $search . '%');
            });
        }

        $surats = $suratsQuery
            ->orderByDesc('sent_at')
            ->get();

        return view('admin-surat', compact('surats', 'tipe', 'search'));
    }

    public function show(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeAccess($surat, $user);
        $isRecipient = $surat->recipient_division === $user->division;

        if ($isRecipient && $surat->archived_at === null) {
            $surat->update([
                'status' => $surat->status === 'Terkirim' ? 'Dibaca' : $surat->status,
                'read_at' => $surat->read_at ?? now(),
            ]);
        }

        $isSender = $surat->sender_user_id === $user->id;

        return view('surat.show', compact('surat', 'isSender', 'isRecipient'));
    }

    public function downloadPdf(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeAccess($surat, $user);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            abort(500, 'PDF module belum terpasang. Jalankan composer require barryvdh/laravel-dompdf.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat.pdf', [
            'surat' => $surat,
        ])->setPaper('a4')
            ->setOptions([
                'isRemoteEnabled' => true,
            ]);

        $filename = 'surat-' . $surat->id . '.pdf';

        return $pdf->stream($filename);
    }

    public function attachment(Request $request, Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeAccess($surat, $user);

        if (!$surat->lampiran_path || !Storage::disk('public')->exists($surat->lampiran_path)) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($surat->lampiran_path);
        $mimeType = Storage::disk('public')->mimeType($surat->lampiran_path) ?: 'application/octet-stream';
        $filename = $surat->lampiran_name ?: basename($surat->lampiran_path);
        $safeFilename = str_replace('"', '', $filename);

        if ($request->boolean('download')) {
            return response()->download($path, $safeFilename, [
                'Content-Type' => $mimeType,
            ]);
        }

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $safeFilename . '"',
        ]);
    }

    public function archive(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeAccess($surat, $user);

        if ($surat->archived_at === null) {
            $surat->update([
                'archived_at' => now(),
            ]);
        }

        return back()->with('status', 'Surat diarsipkan.');
    }

    public function unarchive(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeAccess($surat, $user);

        if ($surat->archived_at !== null) {
            $surat->update([
                'archived_at' => null,
            ]);
        }

        return back()->with('status', 'Surat dikembalikan.');
    }

    public function markDone(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeRecipient($surat, $user);

        $surat->update([
            'status' => 'Selesai',
            'read_at' => $surat->read_at ?? now(),
            'completed_at' => now(),
        ]);

        return redirect()->route('dashboard');
    }

    public function replyForm(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeRecipient($surat, $user);

        return view('surat.reply', compact('surat'));
    }

    public function replyStore(Request $request, Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeRecipient($surat, $user);

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,pdf', 'max:10240'],
        ]);

        $lampiranPath = null;
        $lampiranName = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('attachments', 'public');
            $lampiranName = $request->file('lampiran')->getClientOriginalName();
        }

        $now = now(config('app.timezone'));
        $sequence = $this->nextSequence($user->division, $now->format('Y'));
        $nomorSurat = $this->buildNomorSurat($user->division, $sequence, $now);

        $balasan = Surat::create([
            'parent_id' => $surat->id,
            'sender_user_id' => $user->id,
            'sender_division' => $user->division,
            'recipient_division' => $surat->sender_division,
            'nomor_surat' => $nomorSurat,
            'jenis' => $surat->jenis,
            'judul' => $data['judul'],
            'isi' => $data['isi'],
            'lampiran_path' => $lampiranPath,
            'lampiran_name' => $lampiranName,
            'status' => 'Terkirim',
            'sent_at' => $now,
        ]);

        $surat->update([
            'status' => 'Dibalas',
            'replied_at' => now(),
        ]);

        Notification::create([
            'surat_id' => $balasan->id,
            'recipient_division' => $balasan->recipient_division,
            'message' => 'Balasan surat dari divisi ' . $balasan->sender_division,
        ]);

        return redirect()->route('surat.show', $balasan);
    }

    private function authorizeAccess(Surat $surat, $user): void
    {
        if ($user->role === 'Admin') {
            return;
        }

        if ($surat->sender_user_id === $user->id) {
            return;
        }

        if ($surat->recipient_division === $user->division) {
            return;
        }

        if (in_array($user->division, $surat->cc_divisions ?? [], true)) {
            return;
        }

        abort(403);
    }

    private function authorizeRecipient(Surat $surat, $user): void
    {
        if ($user->role === 'Admin') {
            return;
        }

        if ($surat->recipient_division === $user->division) {
            return;
        }

        abort(403);
    }

    private function nextSequence(string $division, string $year): int
    {
        $count = Surat::where('sender_division', $division)
            ->whereYear('sent_at', $year)
            ->count();

        return $count + 1;
    }

    private function buildNomorSurat(string $division, int $sequence, \Illuminate\Support\Carbon $date): string
    {
        $seq = str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
        $year = $date->format('Y');
        $divisionCode = $this->buildDivisionCode($division);

        return 'No. ' . $seq . '/PAG' . $divisionCode . '/' . $year;
    }

    private function buildDivisionCode(?string $division): string
    {
        if (empty($division)) {
            return '0000';
        }

        $code = Division::query()
            ->where('name', $division)
            ->value('unit_code');

        return !empty($code) ? (string) $code : '0000';
    }
}
