<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $defaults = ['HR', 'Keuangan', 'Operasional', 'IT', 'Legal'];
        $unique = collect($defaults);

        if (Schema::hasTable('users')) {
            $existing = DB::table('users')
                ->whereNotNull('division')
                ->pluck('division');
            $unique = $unique->merge($existing);
        }

        $unique = $unique->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique()
            ->values();

        if ($unique->isNotEmpty()) {
            $now = now();
            DB::table('divisions')->insert(
                $unique->map(fn ($name) => [
                    'name' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
