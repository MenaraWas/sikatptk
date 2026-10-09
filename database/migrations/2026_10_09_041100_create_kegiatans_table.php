<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->cascadeOnDelete();
            $table->foreignId('ptk_id')->nullable()->constrained('ptks')->nullOnDelete();
            $table->unsignedInteger('urutan')->default(1);
            $table->string('jenis_pelatihan'); // Online, Offline, Diklat, Workshop, Seminar, Bimtek, etc.
            $table->text('judul_pelatihan');
            $table->string('penyelenggara');
            $table->date('tanggal_pelaksanaan');
            $table->string('tahun_pelaksanaan', 4);
            $table->unsignedInteger('jumlah_jpl')->default(0);
            $table->string('file_sertifikat');
            $table->string('file_original_name');
            $table->unsignedBigInteger('file_size');
            $table->string('file_mime')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_verifikasi', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
