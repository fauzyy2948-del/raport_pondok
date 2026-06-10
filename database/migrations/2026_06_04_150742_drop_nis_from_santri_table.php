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
        Schema::table('santri', function (Blueprint $table) {
            $table->dropUnique(['nis']);
            $table->dropColumn('nis');
            $table->string('nisn')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('santri', function (Blueprint $table) {
            $table->string('nis')->nullable();
            $table->unique(['nis']);
            $table->dropUnique(['nisn']);
            $table->string('nisn')->nullable()->change();
        });
    }
};
