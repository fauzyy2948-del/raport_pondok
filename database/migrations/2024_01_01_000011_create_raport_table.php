<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raport', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->decimal('rata_rata', 5, 2)->nullable();
            $table->integer('peringkat')->nullable();
            $table->integer('jumlah_siswa')->nullable();
            $table->integer('hadir')->default(0);
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alfa')->default(0);
            $table->text('catatan_wali_kelas')->nullable();
            $table->string('predikat_akhir')->nullable();
            $table->boolean('diterbitkan')->default(false);
            $table->timestamp('diterbitkan_pada')->nullable();
            $table->timestamps();

            $table->unique(['santri_id', 'tahun_ajaran_id']);
        });

        Schema::create('raport_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raport')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->decimal('nilai_harian', 5, 2)->nullable();
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_hafalan', 5, 2)->nullable();
            $table->decimal('nilai_adab', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('predikat')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raport_detail');
        Schema::dropIfExists('raport');
    }
};
