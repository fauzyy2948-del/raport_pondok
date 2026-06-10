<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_pembinaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('ustadz_id')->constrained('ustadz')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('jenis', ['prestasi', 'pelanggaran', 'pembinaan', 'kesehatan', 'lainnya'])->default('pembinaan');
            $table->string('judul');
            $table->text('isi');
            $table->date('tanggal');
            $table->enum('tindak_lanjut', ['tidak_ada', 'perlu_perhatian', 'sudah_ditangani'])->default('tidak_ada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_pembinaan');
    }
};
