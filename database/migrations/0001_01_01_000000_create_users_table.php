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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID unik utama untuk database
            $table->string('name'); 
            $table->string('nisn_nip')->unique(); // 
            $table->string('rfid')->unique()->nullable(); 
            $table->string('password'); 
            $table->string('no_hp')->nullable(); 
            $table->string('kelas_unit_kerja')->nullable(); // Kelas (Siswa) atau Unit Kerja (Guru/Tenaga Pendidik)
            
            // Role user: membedakan hak akses
            $table->enum('role', ['siswa', 'guru', 'tenaga_pendidik', 'teknisi', 'koordinator']); 
            
            $table->rememberToken();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};