<?php
namespace App\Http\Controllers;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori'
        ]);

        // Ambil 3 huruf pertama untuk kode otomatis
        $prefix = strtoupper(substr($request->nama_kategori, 0, 3));

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'kode_prefix'   => $prefix
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }
}