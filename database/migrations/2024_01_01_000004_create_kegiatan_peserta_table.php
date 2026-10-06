<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->enum('status_kehadiran', ['belum', 'hadir', 'izin', 'alpa'])->default('belum');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['kegiatan_id', 'pegawai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_peserta');
    }
};
