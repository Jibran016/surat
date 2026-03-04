<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Notification;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $divisions = Division::orderBy('name')->pluck('name');

        $jenisList = ['Memo', 'Permintaan', 'Laporan'];

        return view('surat.create', compact('user', 'divisions', 'jenisList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'jenis' => ['required', 'string'],
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string'],
            'lampiran' => ['nullable', 'file', 'max:10240'],
            'recipient_divisions' => ['required', 'array', 'min:1'],
            'recipient_divisions.*' => ['required', 'string', 'exists:divisions,name'],
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

        $now = now();
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
            ->route('surat.outbox')
            ->with('status', $statusMessage);
    }

    public function inbox()
    {
        $user = Auth::user();

        $surats = Surat::where('recipient_division', $user->division)
            ->whereNull('archived_at')
            ->orderByDesc('sent_at')
            ->get();

        return view('surat.inbox', compact('surats'));
    }

    public function outbox()
    {
        $user = Auth::user();

        $surats = Surat::where('sender_user_id', $user->id)
            ->whereNull('archived_at')
            ->orderByDesc('sent_at')
            ->get();

        return view('surat.outbox', compact('surats'));
    }

    public function archiveIndex()
    {
        $user = Auth::user();

        $surats = Surat::where(function ($query) use ($user) {
            $query->where('sender_user_id', $user->id)
                ->orWhere('recipient_division', $user->division);
        })->whereNotNull('archived_at')
            ->orderByDesc('archived_at')
            ->get();

        return view('surat.archive', compact('surats'));
    }

    public function show(Surat $surat)
    {
        $user = Auth::user();
        $this->authorizeAccess($surat, $user);

        if ($surat->recipient_division === $user->division && $surat->status === 'Terkirim') {
            $surat->update([
                'status' => 'Dibaca',
                'read_at' => now(),
            ]);
        }

        $isSender = $surat->sender_user_id === $user->id;
        $isRecipient = $surat->recipient_division === $user->division;

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

        return $pdf->download($filename);
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
            'lampiran' => ['nullable', 'file', 'max:10240'],
        ]);

        $lampiranPath = null;
        $lampiranName = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('attachments', 'public');
            $lampiranName = $request->file('lampiran')->getClientOriginalName();
        }

        $now = now();
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
        $divisionCode = strtoupper(preg_replace('/[^A-Z0-9]/', '', $division));
        if ($divisionCode === '') {
            $divisionCode = 'DIV';
        }

        $seq = str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
        $year = $date->format('Y');

        return 'No.' . $seq . '/' . $divisionCode . '/' . $year . '-S0';
    }
}
