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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained()->onDelete('cascade');
            $table->string('kode_rekening');
            $table->unique(['user_id', 'kode_rekening']);
            $table->string('nama_kegiatan');
            $table->string('kategori'); // Contoh: Pembangunan, Pengadaan, dll.
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
