<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sop;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamanUserController extends Controller
{
    public function index()
    {
        // Mengambil SOP Peminjaman untuk ditampilkan ke User
        $sop = Sop::where('jenis', 'peminjaman')->first();
        
        return view('user.peminjaman.index', compact('sop'));
    }

    // Menampilkan Form Pengajuan Surat (Barang Tercatat)
    public function createTercatat()
    {
        // Mengambil daftar barang yang jenisnya 'tercatat' dan stoknya masih ada
        $barangTercatat = \App\Models\Barang::where('jenis_peminjaman', 'tercatat')
                                            ->where('stok_tersedia', '>', 0)
                                            ->get();

        return view('user.peminjaman.tercatat', compact('barangTercatat'));
    }

    public function storeTercatat(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'alasan' => 'required|min:5',
            'barang_id' => 'required|array|min:1',
            'jumlah' => 'required|array'
        ]);

        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'jenis_surat' => $request->jenis_surat,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'alasan' => $request->alasan,
            'status' => 'menunggu_verifikasi',
        ]);

        foreach ($request->barang_id as $id) {
            $peminjaman->barangs()->attach($id, [
                'jumlah' => $request->jumlah[$id]
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Pengajuan surat berhasil dikirim! Silakan cek menu Riwayat secara berkala.');
    }

        // Menampilkan Riwayat Peminjaman User
    public function riwayat()
    {
        // Ambil data peminjaman milik user yang sedang login, urutkan dari yang paling baru
        $riwayats = \App\Models\Peminjaman::where('user_id', \Illuminate\Support\Facades\Auth::id())
                                        ->orderBy('created_at', 'desc')
                                        ->get();

        return view('user.riwayat', compact('riwayats'));
    }

    // 1. Menampilkan Form Katalog Barang Umum
    public function formUmum()
    {
        // Hanya ambil barang yang jenisnya 'umum' dan stoknya masih ada (> 0)
        $barangs = \App\Models\Barang::where('jenis_peminjaman', 'umum')
                                     ->where('stok_tersedia', '>', 0)
                                     ->get();

        return view('user.peminjaman.umum', compact('barangs'));
    }

    // 2. Memproses Peminjaman Barang Umum (Tanpa Surat)
    public function storeUmum(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string|max:255'
        ]);

        $barang = \App\Models\Barang::findOrFail($request->barang_id);

        // Cek apakah stok cukup
        if ($request->jumlah > $barang->stok_tersedia) {
            return back()->with('error', 'Maaf, stok barang tidak mencukupi! Stok tersisa: ' . $barang->stok_tersedia);
        }

        // 1. Buat Data Peminjaman Baru
        $peminjaman = \App\Models\Peminjaman::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'jenis_surat' => 'tanpa_surat', // Penanda barang umum
            'alasan' => $request->alasan,
            'tanggal_pinjam' => \Carbon\Carbon::now(), 
            'tanggal_kembali' => \Carbon\Carbon::now()->endOfDay(), 
            'status' => 'sedang_dipinjam', 
        ]);

        // 2. Simpan ke pivot table peminjaman_barang
        $peminjaman->barangs()->attach($barang->id, ['jumlah' => $request->jumlah]);

        // 3. Kurangi Stok Barang secara Real-time
        $barang->decrement('stok_tersedia', $request->jumlah);

        return redirect()->route('user.riwayat')->with('success', 'Berhasil! Silakan langsung menuju ruang Teknisi untuk mengambil barang.');
    }

   // 3. Menampilkan Halaman Pengembalian Barang Umum
    public function formPengembalian()
    {
        // Ambil data peminjaman yang sedang berlangsung
        $peminjamans = \App\Models\Peminjaman::with('barangs')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('jenis_surat', 'tanpa_surat')
            ->where('status', 'sedang_dipinjam')
            ->orderBy('created_at', 'desc')
            ->get();

        $sopPengembalian = Sop::where('jenis', 'pengembalian')->first();
        return view('user.peminjaman.pengembalian', compact('peminjamans', 'sopPengembalian'));
    }

    // Memproses Pengembalian (Stok Bertambah & Status Selesai)
    public function prosesPengembalian($id)
    {
        $peminjaman = \App\Models\Peminjaman::with('barangs')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($id);

        foreach ($peminjaman->barangs as $barang) {
            $barang->increment('stok_tersedia', $barang->pivot->jumlah);
        }

        $peminjaman->update([
            'status' => 'selesai',
            'tanggal_kembali' => \Carbon\Carbon::now(),
        ]);

        return back()->with('success', 'Barang berhasil dikembalikan! Terima kasih telah merawat inventaris sekolah.');
    }

}