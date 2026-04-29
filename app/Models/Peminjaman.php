<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id', 
        'jenis_surat', 
        'tanggal_pinjam', 
        'tanggal_kembali', 
        'alasan', 
        'status', 
        'teknisi_id', 
        'file_pdf',
        'pesan_penolakan' 
    ];

    // Relasi: Peminjaman ini milik siapa (User)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi: Siapa teknisi yang menangani (User)
    public function teknisi() {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    // Relasi: Daftar barang yang ada di peminjaman ini (Many to Many)
    public function barangs() {
        return $this->belongsToMany(Barang::class, 'peminjaman_barang')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }
}