<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumumans';
    protected $fillable = ['isi_pengumuman', 'aktif_sampai']; 
    protected $casts = [
        'aktif_sampai' => 'datetime',
    ];
}