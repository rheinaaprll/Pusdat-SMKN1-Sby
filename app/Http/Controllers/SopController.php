<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sop;

class SopController extends Controller
{
    public function index()
    {
        // Ambil data dari database (kalau belum ada, isinya akan kosong)
        $sopPeminjaman = Sop::where('jenis', 'peminjaman')->first();
        $sopPengembalian = Sop::where('jenis', 'pengembalian')->first();
        
        return view('teknisi.sop.index', compact('sopPeminjaman', 'sopPengembalian'));
    }

    // Menyimpan atau Mengupdate SOP
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:peminjaman,pengembalian',
            'isi_sop' => 'nullable|string'
        ]);
        
        Sop::updateOrCreate(
            ['jenis' => $request->jenis],
            ['isi_sop' => $request->isi_sop]
        );

        $namaSop = $request->jenis == 'peminjaman' ? 'Peminjaman' : 'Pengembalian';

        return back()->with('success', "Teks SOP $namaSop berhasil diperbarui!");
    }
}