<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
     public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique(); // Contoh: TKR-001
            $table->string('nama_barang'); // Contoh: Obeng Plus
            $table->string('kategori'); // Contoh: Perkakas, Elektronik, dll
            $table->string('merk')->nullable(); // Boleh kosong
            $table->integer('stok_total'); // Total barang keseluruhan
            $table->integer('stok_tersedia'); // Barang yang sedang nganggur (bisa dipinjam)
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->text('keterangan')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
