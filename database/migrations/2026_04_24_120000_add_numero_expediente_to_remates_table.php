<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remates', function (Blueprint $table): void {
            $table->string('numero_expediente', 30)->nullable()->after('foto_path');
        });

        DB::table('remates')->select(['id', 'fecha_expediente'])->orderBy('id')->chunkById(100, function ($rows): void {
            foreach ($rows as $row) {
                $year = date('Y', strtotime((string) $row->fecha_expediente));
                $number = str_pad((string) $row->id, 3, '0', STR_PAD_LEFT);
                DB::table('remates')
                    ->where('id', $row->id)
                    ->update(['numero_expediente' => 'EXP. '.$number.'-'.$year]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('remates', function (Blueprint $table): void {
            $table->dropColumn('numero_expediente');
        });
    }
};
