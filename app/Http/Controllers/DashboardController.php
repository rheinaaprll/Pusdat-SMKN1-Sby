<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Pengumuman;

class DashboardController extends Controller
{

// --- Dashboard Khusus User (Siswa, Guru, Tenaga Pendidik) ---
    public function userIndex()
    {
        $pengumuman = Pengumuman::whereNotNull('isi_pengumuman')
                        ->where('aktif_sampai', '>', now())
                        ->first();
        return view('user.dashboard', compact('pengumuman'));
    }

    //  ---- Dashboard Teknisi ---- //
    public function teknisiIndex()
    {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalBarang = \App\Models\Barang::count(); 
        $barangDipinjam = \App\Models\Barang::whereColumn('stok_tersedia', '<', 'stok_total')
                        ->sum(\Illuminate\Support\Facades\DB::raw('stok_total - stok_tersedia'));
        $pengajuanSurat = \App\Models\Peminjaman::where('status', 'menunggu_verifikasi')->count();
        $kumpulanTips = [
            "Berdoa sebelum memulai hari. Pastikan untuk selalu memeriksa kelengkapan dan kondisi fisik barang sebelum diserahkan kepada peminjam.",
            "Jangan lupa senyum! ^_^ Pelayanan yang ramah akan membuat orang lain merasa nyaman dan dihargai.",
            "Selalu ingat untuk sarapan! Periksa kembali stok alat yang sering dipinjam. Jika ada yang rusak, segera tandai di sistem.",
            "Pastikan setiap peminjam sudah terdata dengan benar di sistem sebelum membawa keluar barang dari ruang alat. Ketika jam istirahat, jangan lupa rehat sejenak ya!",
            "Alat dan barang elektronik butuh perawatan ekstra. Ingatkan peminjam untuk menggunakannya sesuai SOP. Jangan lupa sayangi diri sendiri juga ya.",
            "Rapikan kembali etalase dan ruang alat sebelum jam pulang agar besok pagi siap digunakan beraktivitas. Jangan lupa matikan AC sebelum pulang!",
            "Cek daftar pengajuan peminjaman hari ini secara berkala, jangan sampai ada permohonan yang terlewat! Ketika pulang pastikan mematikan lampu, untuk hemat energi."
        ];
        $tipHariIni = $kumpulanTips[array_rand($kumpulanTips)];

        return view('teknisi.dashboard', compact('totalSiswa', 'totalBarang', 'barangDipinjam', 'pengajuanSurat', 'tipHariIni'));
    }


    // --- Dashboard Koordinator ---- //
    public function koordinatorIndex()
    {
        // 1. Data Statistik Atas
        $totalSiswa = \App\Models\User::where('role', 'siswa')->count();
        $totalBarang = \App\Models\Barang::count();
        
        // Menghitung barang yang sedang di luar (selisih stok total dan tersedia)
        $barangKeluar = \App\Models\Barang::whereColumn('stok_tersedia', '<', 'stok_total')
                        ->sum(DB::raw('stok_total - stok_tersedia'));
        
        // Pengajuan yang butuh aksi Koordinator (Tugas Real-time)
        $menungguAksi = \App\Models\Peminjaman::whereIn('status', ['bisa_diunduh', 'sedang_dipinjam'])->count(); 

        // 2. Data Real untuk Grafik (Berdasarkan database peminjaman tahun ini)
        $grafik = \App\Models\Peminjaman::selectRaw('MONTHNAME(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderByRaw('MIN(created_at) ASC')
            ->get();

        $dataGrafik = [];
        foreach ($grafik as $g) {
            $dataGrafik[] = [
                'bulan' => substr($g->bulan, 0, 3), 
                'jumlah' => $g->jumlah
            ];
        }
        
        if (empty($dataGrafik)) {
            $dataGrafik = [
                ['bulan' => 'Jan', 'jumlah' => 0]
            ];
        }

        $aktivitasTerbaru = \App\Models\Peminjaman::with(['user', 'barangs'])  // 3. Aktivitas Terbaru (5 peminjaman terakhir yang ada pergerakan)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('koordinator.dashboard', compact(
            'totalSiswa', 'totalBarang', 'barangKeluar', 'menungguAksi', 'dataGrafik', 'aktivitasTerbaru'
        ));
    }

}