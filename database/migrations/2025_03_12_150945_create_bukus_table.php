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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('KategoriBuku')->onDelete('cascade');
            $table->string('nama_buku');
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->datetime('tahun_penerbitan');
            $table->integer('jumlah_tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
