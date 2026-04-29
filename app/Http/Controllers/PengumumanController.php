<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    public function index()
    {
        // Ambil data pengumuman pertama (karena sistem hanya butuh 1 slot pengumuman)
        $pengumuman = Pengumuman::first(); 
        return view('teknisi.pengumuman.index', compact('pengumuman'));
    }

    // Memproses simpan data & hitung waktu aktif
    public function store(Request $request)
    {
        // Cari pengumuman yang ada, kalau belum ada sama sekali, buat baru
        $pengumuman = Pengumuman::first() ?? new Pengumuman();

        // Jika kotak isian dikosongkan, berarti pengumuman dihapus/hilang dari dashboard user
        if (empty($request->isi_pengumuman)) {
            $pengumuman->isi_pengumuman = null;
            $pengumuman->aktif_sampai = null;
            $pengumuman->save();

            return back()->with('success', 'Pengumuman berhasil dihapus dan dinonaktifkan.');
        }

        //  Hitung Durasi (Jam/Hari/Bulan)
        $durasi = $request->durasi;
        $waktuAktif = Carbon::now(); 

        if (str_contains($durasi, 'jam')) {
            $angka = (int) str_replace('_jam', '', $durasi);
            $waktuAktif->addHours($angka);
        } elseif (str_contains($durasi, 'hari')) {
            $angka = (int) str_replace('_hari', '', $durasi);
            $waktuAktif->addDays($angka);
        } elseif (str_contains($durasi, 'bulan')) {
            $angka = (int) str_replace('_bulan', '', $durasi);
            $waktuAktif->addMonths($angka);
        }

        $pengumuman->isi_pengumuman = $request->isi_pengumuman;
        $pengumuman->aktif_sampai = $waktuAktif;
        $pengumuman->save();

        return back()->with('success', 'Pengumuman berhasil diposting! Akan aktif hingga: ' . $waktuAktif->translatedFormat('d F Y H:i'));
    }
}