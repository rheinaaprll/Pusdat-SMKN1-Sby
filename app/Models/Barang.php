<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'merk',
        'stok_total',
        'stok_tersedia',
        'kondisi',
        'keterangan',
        'jenis_peminjaman',
    ];

    public function peminjamans() {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_barang')
                    ->withPivot('jumlah');
    }
}