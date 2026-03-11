<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisions', function (Blueprint $table): void {
            $table->string('unit_code', 10)->nullable()->after('name');
        });

        $map = [
            'Technical & Operation' => '1000',
            'Technical & Service' => '1100',
            'Production' => '1200',
            'HSE & QM' => '1300',
            'Legal' => '1400',
            'Finance Operation' => '1500',
            'Procurement' => '1600',
            'HR Development' => '1700',
        ];

        foreach ($map as $name => $code) {
            DB::table('divisions')
                ->where('name', $name)
                ->update(['unit_code' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table): void {
            $table->dropColumn('unit_code');
        });
    }
};

