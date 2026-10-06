<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->foreignId('dari_pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->foreignId('kepada_pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'dibaca', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->datetime('dibaca_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
