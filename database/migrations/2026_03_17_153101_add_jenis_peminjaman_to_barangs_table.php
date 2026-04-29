<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
 {
     Schema::table('barangs', function (Blueprint $table) {
         // Menambahkan kolom pembeda setelah kolom kategori
         $table->enum('jenis_peminjaman', ['tercatat', 'umum'])->default('umum')->after('kategori');
     });
 }

 public function down(): void
 {
     Schema::table('barangs', function (Blueprint $table) {
         $table->dropColumn('jenis_peminjaman');
     });
 }
};
