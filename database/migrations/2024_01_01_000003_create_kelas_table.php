<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g. Kelas 1A, Kamar A
            $table->string('tingkat'); // 1, 2, 3, etc.
            $table->enum('jenis', ['diniyah', 'umum', 'campuran'])->default('campuran');
            $table->integer('kapasitas')->default(30);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
