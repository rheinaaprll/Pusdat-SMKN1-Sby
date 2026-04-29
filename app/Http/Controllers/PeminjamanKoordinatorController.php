<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class PeminjamanKoordinatorController extends Controller
{
    // 1. Menampilkan Halaman Dashboard / Menara Pemantau Koordinator
    public function index()
    {
        // Hanya ambil surat yang statusnya 'bisa_diunduh' (siap diambil) 
        // atau 'sedang_dipinjam' (belum dikembalikan)
        $peminjamans = Peminjaman::with(['user', 'barangs'])
            ->whereIn('status', ['bisa_diunduh', 'sedang_dipinjam'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('koordinator.peminjaman.index', compact('peminjamans'));
    }

    // 2. Fungsi Mengeluarkan Barang (STOK BERKURANG)
    public function keluarkanBarang($id)
    {
        $peminjaman = Peminjaman::with('barangs')->findOrFail($id);

        // Looping semua barang yang ada di dalam surat tersebut, lalu kurangi stoknya
        foreach ($peminjaman->barangs as $barang) {
            // Mengurangi 'stok_tersedia' sebanyak 'jumlah' yang dipinjam user
            $barang->decrement('stok_tersedia', $barang->pivot->jumlah);
        }

        $peminjaman->update([
            'status' => 'sedang_dipinjam'
        ]);

        return back()->with('success', 'Barang berhasil dikeluarkan kepada User. Stok Gudang telah dikurangi!');
    }

    // 3. Fungsi Menerima Pengembalian Barang (STOK BERTAMBAH)
    public function terimaKembali($id)
    {
        $peminjaman = Peminjaman::with('barangs')->findOrFail($id);

        // Looping semua barang, lalu kembalikan/tambahkan stoknya
        foreach ($peminjaman->barangs as $barang) {
            // Menambah 'stok_tersedia' sebanyak 'jumlah' yang dipinjam user
            $barang->increment('stok_tersedia', $barang->pivot->jumlah);
        }

        $peminjaman->update([
            'status' => 'selesai'
        ]);

        return back()->with('success', 'Barang telah diterima kembali. Stok Gudang berhasil ditambahkan!');
    }
}