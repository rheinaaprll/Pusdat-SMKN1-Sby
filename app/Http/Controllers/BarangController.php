<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
class BarangController extends Controller

{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');  
        $query = Barang::query();

        // 1. Logika Pencarian (Berdasarkan Nama atau Kode Barang)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // 2. Logika Filter Alat & Barang
        if ($filter == 'tersedia') {
            $query->where('stok_tersedia', '>', 0);
        } elseif ($filter == 'dipinjam') {
            $query->whereColumn('stok_tersedia', '<', 'stok_total');
        }

        // 3. Pengurutan & Pagination (Urut Abjad Kategori -> lalu Kode Barang)
        $barangs = $query->orderBy('kategori', 'asc')
                         ->orderBy('kode_barang', 'asc')
                         ->paginate(10); 

        return view('teknisi.barang.index', compact('barangs', 'search', 'filter'));
    }

    // 1. Menampilkan halaman form tambah barang
     public function create()
    {
        $kategoris = \App\Models\Kategori::all(); // Ambil semua kategori dari DB
        return view('teknisi.barang.create', compact('kategoris'));
    }

    // 2. Memproses penyimpanan data dan MENCIPTAKAN KODE OTOMATIS  
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'merk'        => 'nullable|string|max:255',
            'stok_total'  => 'required|integer|min:1',
            'keterangan'  => 'nullable|string',
            'jenis_peminjaman' => 'required|in:tercatat,umum'
        ]);

        // Ambil 3 huruf pertama dari Kategori, lalu jadikan Huruf Besar. 
        $prefix = strtoupper(substr($request->kategori, 0, 3));
        $barangTerakhir = Barang::where('kode_barang', 'like', $prefix . '-%')
                                ->orderBy('id', 'desc')
                                ->first();

        // Tentukan nomor urut
        if ($barangTerakhir) {
            $nomorTerakhir = (int) substr($barangTerakhir->kode_barang, 4);
            $nomorBaru = $nomorTerakhir + 1;
        } else {
            $nomorBaru = 1;
        }

        // D. Gabungkan Prefix + Nomor Baru dengan format 4 digit
        $kodeOtomatis = $prefix . '-' . str_pad($nomorBaru, 4, '0', STR_PAD_LEFT);
        Barang::create([
            'kode_barang'   => $kodeOtomatis,
            'nama_barang'   => $request->nama_barang,
            'kategori'      => $request->kategori,
            'merk'          => $request->merk,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total, // Saat pertama masuk, stok_tersedia PASTI sama dengan stok_total (belum ada yg pinjam)
            'kondisi'       => 'Baik', 
            'keterangan'    => $request->keterangan,
            'jenis_peminjaman' => $request->jenis_peminjaman,
        ]);

        return redirect()->route('teknisi.barang.index')
            ->with('success', 'Barang berhasil ditambahkan dengan kode: ' . $kodeOtomatis);
    }

    // 3. halaman form edit barang
    public function edit($id)
    {
        // Cari barang berdasarkan ID, jika tidak ada akan muncul error 404
        $barang = Barang::findOrFail($id); 
        $kategoris = \App\Models\Kategori::all(); 
        
        return view('teknisi.barang.edit', compact('barang', 'kategoris'));
    }

    // 4. Memproses update/perubahan data ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'merk'        => 'nullable|string|max:255',
            'stok_total'  => 'required|integer|min:1',
            'keterangan'  => 'nullable|string',
            'jenis_peminjaman' => '$request->jenis_peminjaman'
        ]);

        $barang = Barang::findOrFail($id);
        $selisihStok = $request->stok_total - $barang->stok_total;
        $stokTersediaBaru = $barang->stok_tersedia + $selisihStok;

        // Mencegah error jika stok total diubah jadi lebih kecil dari barang yang sedang dipinjam
        if ($stokTersediaBaru < 0) {
            return back()->with('error', 'Gagal! Stok total tidak boleh lebih kecil dari jumlah barang yang saat ini sedang dipinjam.');
        }

        // Update data
        $barang->update([
            'nama_barang'   => $request->nama_barang,
            'kategori'      => $request->kategori,
            'merk'          => $request->merk,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $stokTersediaBaru, 
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('teknisi.barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    // 5. penghapusan data
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        
        // teknisi tidak bisa menghapus barang yang masih ada di tangan peminjam
        if ($barang->stok_tersedia < $barang->stok_total) {
            return redirect()->route('teknisi.barang.index')->with('error', 'Gagal! Barang ini tidak bisa dihapus karena masih ada yang sedang dipinjam.');
        }

        $barang->delete(); // Hapus dari database
        return redirect()->route('teknisi.barang.index')->with('success', 'Data barang berhasil dihapus permanen!');
    }
}