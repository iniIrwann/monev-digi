<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id')->constrained()->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained()->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_kegiatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('verifikasi_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('tahap')->nullable(); // 1 atau 2
            $table->integer('volume_keluaran')->nullable();
            $table->string('uraian_keluaran')->nullable();
            $table->integer('tenaga_kerja')->nullable();
            $table->string('cara_pengadaan')->nullable();
            $table->bigInteger('realisasi_keuangan')->nullable();
            $table->integer('durasi')->nullable();
            $table->bigInteger('upah')->nullable();
            $table->integer('KPM')->nullable();
            $table->bigInteger('BLT')->nullable();
            $table->integer('tahun')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasis');
    }
};
