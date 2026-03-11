<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Notification;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        $now = now();

        $divisions = [
            ['name' => 'Technical & Operation', 'unit_code' => '1000'],
            ['name' => 'Technical & Service', 'unit_code' => '1100'],
            ['name' => 'Production', 'unit_code' => '1200'],
            ['name' => 'HSE & QM', 'unit_code' => '1300'],
            ['name' => 'Legal', 'unit_code' => '1400'],
            ['name' => 'HR Development', 'unit_code' => '1700'],
            ['name' => 'Finance Operation', 'unit_code' => '1500'],
            ['name' => 'Procurement', 'unit_code' => '1600'],
        ];

        Division::query()
            ->whereNotIn('name', collect($divisions)->pluck('name')->all())
            ->delete();

        Division::upsert(
            collect($divisions)->map(fn (array $division) => [
                'name' => $division['name'],
                'unit_code' => $division['unit_code'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->all(),
            ['name'],
            ['unit_code', 'updated_at']
        );

        $users = [
            ['username' => 'admin', 'division' => null, 'role' => 'Admin', 'email' => 'admin@suratin.local'],
            ['username' => 'Technical & Operation', 'division' => 'Technical & Operation', 'role' => 'User', 'email' => 'tech.operation@suratin.local'],
            ['username' => 'Technical & Service', 'division' => 'Technical & Service', 'role' => 'User', 'email' => 'tech.service@suratin.local'],
            ['username' => 'Production', 'division' => 'Production', 'role' => 'User', 'email' => 'production@suratin.local'],
            ['username' => 'HSE & QM', 'division' => 'HSE & QM', 'role' => 'User', 'email' => 'hseqm@suratin.local'],
            ['username' => 'Legal', 'division' => 'Legal', 'role' => 'User', 'email' => 'legal@suratin.local'],
            ['username' => 'HR Development', 'division' => 'HR Development', 'role' => 'User', 'email' => 'hrdev@suratin.local'],
            ['username' => 'Finance Operation', 'division' => 'Finance Operation', 'role' => 'User', 'email' => 'finance.operation@suratin.local'],
            ['username' => 'Procurement', 'division' => 'Procurement', 'role' => 'User', 'email' => 'procurement@suratin.local'],
        ];

        User::query()->update(['password' => $password]);

        foreach ($users as $item) {
            $existing = User::query()
                ->where('email', $item['email'])
                ->first();

            if (!$existing) {
                $existing = User::query()
                    ->where('username', $item['username'])
                    ->first();
            }

            if ($existing) {
                User::query()
                    ->where('id', '!=', $existing->id)
                    ->where('email', $item['email'])
                    ->update(['email' => null]);

                $usernameConflicts = User::query()
                    ->where('id', '!=', $existing->id)
                    ->where('username', $item['username'])
                    ->get();

                foreach ($usernameConflicts as $conflict) {
                    $fallback = substr($conflict->username, 0, 38) . '-old-' . $conflict->id;
                    $conflict->update(['username' => $fallback]);
                }

                $existing->update([
                    'email' => $item['email'],
                    'username' => $item['username'],
                    'division' => $item['division'],
                    'role' => $item['role'],
                    'password' => $password,
                ]);
            } else {
                User::create([
                    'email' => $item['email'],
                    'username' => $item['username'],
                    'division' => $item['division'],
                    'role' => $item['role'],
                    'password' => $password,
                ]);
            }
        }

        // Seed surat agar setiap akun (divisi) memiliki surat masuk dan keluar dengan jumlah acak.
        Notification::query()->delete();
        Surat::query()->delete();

        $divisionCodes = Division::query()->pluck('unit_code', 'name');
        $divisionNames = Division::query()->pluck('name')->values();
        $usersWithDivision = User::query()->whereNotNull('division')->get();

        $jenisList = ['Permintaan', 'Memorandum', 'Laporan'];
        $judulList = [
            'Permintaan data pendukung',
            'Permohonan tindak lanjut',
            'Laporan progres kegiatan',
            'Informasi jadwal kerja',
            'Permintaan konfirmasi',
            'Koordinasi internal divisi',
        ];
        $isiList = [
            'Dengan hormat, mohon dukungan data dan tindak lanjut sesuai kebutuhan divisi. Terima kasih.',
            'Kami informasikan progres kegiatan terakhir dan mohon arahan lanjutan.',
            'Mohon penjadwalan rapat koordinasi untuk pembahasan berikutnya.',
            'Berikut laporan singkat hasil evaluasi minggu ini.',
            'Mohon konfirmasi atas rencana kerja yang telah diajukan.',
        ];

        $sequenceByDivision = [];
        $buildNomorSurat = function (string $division, \Illuminate\Support\Carbon $date) use (&$sequenceByDivision, $divisionCodes): string {
            $sequenceByDivision[$division] = ($sequenceByDivision[$division] ?? 0) + 1;
            $seq = str_pad((string) $sequenceByDivision[$division], 3, '0', STR_PAD_LEFT);
            $year = $date->format('Y');
            $code = $divisionCodes[$division] ?? '0000';

            return 'No. ' . $seq . '/PAG' . $code . '/' . $year;
        };

        $makeSurat = function (User $sender, string $recipientDivision, ?string $statusOverride = null, bool $forceNotArchived = false) use (
            $jenisList,
            $judulList,
            $isiList,
            $buildNomorSurat
        ): Surat {
            $sentAt = now()->subDays(rand(1, 90))->subMinutes(rand(0, 600));
            $status = $statusOverride ?? Arr::random(['Terkirim', 'Dibaca', 'Dibalas', 'Selesai']);

            $readAt = null;
            $repliedAt = null;
            $completedAt = null;
            $archivedAt = null;

            if ($status !== 'Terkirim') {
                $readAt = (clone $sentAt)->addHours(rand(1, 48));
            }
            if ($status === 'Dibalas') {
                $repliedAt = (clone $readAt)->addHours(rand(1, 48));
            }
            if ($status === 'Selesai') {
                $completedAt = (clone $readAt)->addHours(rand(1, 48));
            }
            if (!$forceNotArchived && rand(1, 10) <= 2) {
                $archivedAt = (clone ($completedAt ?? $readAt ?? $sentAt))->addDays(rand(1, 30));
            }

            $surat = Surat::create([
                'parent_id' => null,
                'sender_user_id' => $sender->id,
                'sender_division' => $sender->division,
                'recipient_division' => $recipientDivision,
                'nomor_surat' => $buildNomorSurat($sender->division, $sentAt),
                'jenis' => Arr::random($jenisList),
                'judul' => Arr::random($judulList),
                'isi' => Arr::random($isiList),
                'lampiran_path' => null,
                'lampiran_name' => null,
                'status' => $status,
                'sent_at' => $sentAt,
                'read_at' => $readAt,
                'replied_at' => $repliedAt,
                'completed_at' => $completedAt,
                'archived_at' => $archivedAt,
            ]);

            Notification::create([
                'surat_id' => $surat->id,
                'recipient_division' => $surat->recipient_division,
                'message' => 'Surat baru dari divisi ' . $surat->sender_division,
                'read_at' => $status !== 'Terkirim' ? $readAt : null,
            ]);

            return $surat;
        };

        $minOutgoing = 2;
        $maxOutgoing = 6;
        $minIncoming = 2;
        $maxIncoming = 6;

        foreach ($usersWithDivision as $user) {
            $outgoingTarget = rand($minOutgoing, $maxOutgoing);
            $recipientPool = $divisionNames->filter(fn ($name) => $name !== $user->division)->values();

            for ($i = 0; $i < $outgoingTarget; $i++) {
                $recipientDivision = $recipientPool->random();
                $forceTerkirim = $i === 0;
                $status = $forceTerkirim ? 'Terkirim' : null;
                $makeSurat($user, $recipientDivision, $status, $forceTerkirim);
            }
        }

        foreach ($usersWithDivision as $user) {
            $targetIncoming = rand($minIncoming, $maxIncoming);
            $incomingCount = Surat::query()
                ->where('recipient_division', $user->division)
                ->count();

            while ($incomingCount < $targetIncoming) {
                $sender = $usersWithDivision->where('id', '!=', $user->id)->random();
                $makeSurat($sender, $user->division);
                $incomingCount++;
            }
        }
    }
}
