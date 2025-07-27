<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained()->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_kegiatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('uraian_keluaran');
            $table->integer('volume_keluaran');
            $table->string('cara_pengadaan'); // Cara pengadaan barang/jasa
            $table->bigInteger('anggaran_target')->nullable();
            $table->bigInteger('realisasi_keuangan')->nullable();
            $table->integer('tenaga_kerja')->nullable();
            $table->integer('durasi')->nullable();
            $table->bigInteger('upah')->nullable();
            $table->integer('KPM')->nullable(); // Keluarga Penerima Manfaat
            $table->bigInteger('BLT')->nullable(); // Bantuan Langsung Tunai
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
        Schema::dropIfExists('targets');
    }
};
