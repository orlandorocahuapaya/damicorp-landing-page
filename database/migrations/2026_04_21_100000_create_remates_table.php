<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remates', function (Blueprint $table): void {
            $table->id();
            $table->string('foto_path');
            $table->date('fecha_expediente');
            $table->string('ubicacion_inmueble', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remates');
    }
};
