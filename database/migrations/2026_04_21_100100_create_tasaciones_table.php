<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasaciones', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('remate_id')->constrained('remates')->cascadeOnDelete();
            $table->decimal('precio_base', 14, 2);
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasaciones');
    }
};
