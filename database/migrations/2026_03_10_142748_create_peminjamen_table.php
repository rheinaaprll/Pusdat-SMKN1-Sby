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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis_surat', ['jurusan', 'ekstrakurikuler', 'ekskul_ke_jurusan', 'guru_tendi']);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->text('alasan');
            
            // Status alur peminjaman
            $table->enum('status', [
                'menunggu_verifikasi', 
                'bisa_diunduh', 
                'sedang_dipinjam', 
                'selesai', 
                'ditolak'
            ])->default('menunggu_verifikasi');

            // ID Teknisi yang memverifikasi/mengedit surat
            $table->foreignId('teknisi_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Path file PDF yang di-generate sistem
            $table->string('file_pdf')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
