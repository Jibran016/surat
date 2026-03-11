<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Notification;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
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

        $jenisList = ['Permintaan', 'Memorandum', 'Laporan'];

        return view('surat.create', compact('user', 'divisions', 'jenisList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'jenis' => ['required', 'string'],
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,pdf', 'max:10240'],
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

        $recipients = collect($data['recipient_divisions'])
            ->filter()
            ->unique()
            ->values();

        $now = now(config('app.timezone'));
        $sequence = $this->nextSequence($user->division, $now->format('Y'));
        $lastSurat = null;

        foreach ($recipients as $recipientDivision) {
            $nomorSurat = $this->buildNomorSurat($user->division, $sequence, $now);
            $sequence++;

            $surat = Surat::create([
                'parent_id' => null,
                'sender_user_id' => $user->id,
                'sender_division' => $user->division,
                'recipient_division' => $recipientDivision,
                'nomor_surat' => $nomorSurat,
                'jenis' => $data['jenis'],
                'judul' => $data['judul'],
                'isi' => $data['isi'],
                'lampiran_path' => $lampiranPath,
                'lampiran_name' => $lampiranName,
                'status' => 'Terkirim',
                'sent_at' => $now,
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
            ->route('dashboard')
            ->with('status', $statusMessage);
    }

    public function inbox()
    {
        $user = Auth::user();

        $unreadSurats = Surat::where('recipient_division', $user->division)
            ->whereNull('archived_at')
            ->where('status', 'Terkirim')
            ->orderByDesc('sent_at')
            ->get();

        $readSurats = Surat::where('recipient_division', $user->division)
            ->whereNull('archived_at')
            ->where('status', '!=', 'Terkirim')
            ->orderByDesc('sent_at')
            ->get();

        return view('surat.inbox', compact('unreadSurats', 'readSurats'));
    }

    public function outbox()
    {
        $user = Auth::user();

        $surats = Surat::where('sender_user_id', $user->id)
            ->whereNull('archived_at')
            ->where('status', 'Terkirim')
            ->orderByDesc('sent_at')
            ->get();

        return view('surat.outbox', compact('surats'));
    }

    public function archiveIndex()
    {
        $user = Auth::user();
        $tipe = request()->query('tipe', 'all');
        if (!in_array($tipe, ['all', 'masuk', 'keluar'], true)) {
            $tipe = 'all';
        }

        $surats = Surat::query()
            ->where('recipient_division', $user->division)
            ->whereNotNull('archived_at')
            ->when($tipe === 'keluar', function ($query) use ($user) {
                $query->where('sender_user_id', $user->id);
            })
            ->when($tipe === 'masuk', function ($query) use ($user) {
                $query->where('sender_user_id', '!=', $user->id);
            })
            ->orderByDesc('sent_at')
            ->get();

        return view('surat.archive', compact('surats', 'tipe'));
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
                'archived_at' => now(),
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

        $surat->update([
            'archived_at' => now(),
        ]);

        return redirect()->route('surat.archive');
    }

    public function markDone(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeRecipient($surat, $user);

        $surat->update([
            'status' => 'Selesai',
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
