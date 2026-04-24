<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasaciones', function (Blueprint $table): void {
            $table->time('hora')->nullable()->after('fecha');
        });

        DB::table('tasaciones')
            ->whereNull('hora')
            ->update(['hora' => '16:00:00']);
    }

    public function down(): void
    {
        Schema::table('tasaciones', function (Blueprint $table): void {
            $table->dropColumn('hora');
        });
    }
};
