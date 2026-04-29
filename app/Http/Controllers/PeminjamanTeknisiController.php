<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
//use App\Models\User;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;

class PeminjamanTeknisiController extends Controller
{
    public function index()
    {
        $antreanSurat = Peminjaman::with('user')
                                  ->where('status', 'menunggu_verifikasi')
                                  ->orderBy('created_at', 'desc')
                                  ->get();
        return view('teknisi.peminjaman.index', compact('antreanSurat'));
    }

    // Menampilkan Detail Surat & Form Verifikasi
   public function show($id)
    {
        $peminjaman = \App\Models\Peminjaman::with(['user', 'barangs'])->findOrFail($id);
        $gurus = \App\Models\User::whereIn('role', ['guru', 'tendik'])->orderBy('name', 'asc')->get();

        // FILTER: Hanya ambil barang yang jenisnya "tercatat"
        $barangs = \App\Models\Barang::where('jenis_peminjaman', 'tercatat')->get();

        return view('teknisi.peminjaman.show', compact('peminjaman', 'gurus', 'barangs'));
    }

    // Hapus Barang dari Pengajuan
    public function hapusBarang($id, $barang_id)
    {
        $peminjaman = \App\Models\Peminjaman::findOrFail($id);
        // Menghapus relasi barang dari pivot table peminjaman_barang
        $peminjaman->barangs()->detach($barang_id);
        
        return back()->with('success', 'Barang berhasil dihapus dari daftar pengajuan.');
    }

    // Fungsi Tambah Barang Pengganti
    public function tambahBarang(Request $request, $id)
    {
        $peminjaman = \App\Models\Peminjaman::findOrFail($id);
             if ($peminjaman->barangs->contains($request->barang_id)) 
                {
            return back()->with('error', 'Barang tersebut sudah ada di daftar pengajuan!');
                 }
        $peminjaman->barangs()->attach($request->barang_id, ['jumlah' => $request->jumlah]);
        return back()->with('success', 'Barang tambahan berhasil dimasukkan.');
    }

    // Proses Verifikasi & Cetak PDF
    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::with(['user', 'barangs'])->findOrFail($id);

        // 1. Template berdasarkan Jenis Surat
        $templateName = '';
        if ($peminjaman->jenis_surat == 'jurusan') {
            $templateName = 'template_jurusan.pdf';
        } elseif ($peminjaman->jenis_surat == 'ekstrakurikuler') {
            $templateName = 'template_ekstrakurikuler_organisasi.pdf';
        } elseif ($peminjaman->jenis_surat == 'ekskul_ke_jurusan') {
            $templateName = 'template_ekskul_ke_jurusan.pdf';
        } elseif ($peminjaman->jenis_surat == 'guru_tendi') {
            $templateName = 'template_guru_tendik.pdf';
        }

        // Ambil file dari folder storage/app/template/
        $pathTemplate = storage_path('app/template/' . $templateName);
        if (!file_exists($pathTemplate)) {
            return back()->with('error', 'Template PDF tidak ditemukan di sistem!');
        }

        // 2. Mulai Mesin FPDI
        $pdf = new Fpdi();
        
         // --- HALAMAN 1 : DATA SURAT ---
        $pdf->setSourceFile($pathTemplate);
        $tplId1 = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($tplId1);
        
        $pdf->SetFont('Times', '', 11); 

        // 1. Tanggal (Kanan Atas)
        $tanggalSekarang = 'Surabaya, ' . Carbon::now()->translatedFormat('d F Y');
        $pdf->SetXY(145, 62); 
        $pdf->Write(0, $tanggalSekarang);

        // 2. DATA DIRI  
        $pdf->SetFont('Times', '', 10); 
        
        $batasKiri = 70; 
        $awalY = 105;
        $jarak = 6.2; 

        $pdf->SetXY($batasKiri, $awalY); $pdf->Write(0, $peminjaman->user->name);
        $pdf->SetXY($batasKiri, $awalY + $jarak); $pdf->Write(0, $peminjaman->user->nisn_nip ?? '-');
        $pdf->SetXY($batasKiri, $awalY + ($jarak * 2)); $pdf->Write(0, $peminjaman->user->kelas_unit_kerja ?? '-');
        $pdf->SetXY($batasKiri, $awalY + ($jarak * 3)); $pdf->Write(0, $peminjaman->user->no_hp ?? '-');
        $pdf->SetXY($batasKiri, $awalY + ($jarak * 4)); $pdf->Write(0, Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y'));
        $pdf->SetXY($batasKiri, $awalY + ($jarak * 5)); $pdf->Write(0, Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d F Y'));

        // 3. Alasan Peminjaman  
        $pdf->SetFont('Times', '', 11); 
        $pdf->SetXY(23, 146);  
        $pdf->Write(0, ' "' . $peminjaman->alasan . '"'); 

        // 4. NAMA-NAMA TTD & NIP 
        $pdf->SetFont('Times', '', 12); 

        // --- A. PEMOHON/PEMINJAM DI KANAN ATAS ---
        $pdf->SetXY(130, 191); $pdf->Write(0, $peminjaman->user->name);
        $pdf->SetXY(141.5, 197.5); $pdf->Write(0, $peminjaman->user->nisn_nip);

        // --- B. POSISI TTD BERDASARKAN JENIS SURAT ---
        
        // 1. otomatis user Koordinator Teknisi 
        $koordinator = \App\Models\User::where('role', 'koordinator_teknisi')->orWhere('role', 'koordinator')->first();
        $namaKoordinator = $koordinator ? $koordinator->name : 'Nama Koordinator';
        $nipKoordinator = $koordinator ? $koordinator->nisn_nip : '-';

        // 1. GURU DAN TENAGA PENDIDIK
        if ($peminjaman->jenis_surat == 'guru_tendi') {
            // FORMAT 3 SEJAJAR (Sarpras - Koordinator - Pemohon)
            if ($request->waka_sarpras) {
                $pdf->SetXY(14, 191); $pdf->Write(0, $request->waka_sarpras);
                $guru = \App\Models\User::where('name', $request->waka_sarpras)->first();
                if($guru) { $pdf->SetXY(23, 197.5); $pdf->Write(0, $guru->nisn_nip); }
            }
            
            // TTD Koordinator Teknisi (Tengah)
            $pdf->SetXY(72, 191); $pdf->Write(0, $namaKoordinator);
            $pdf->SetXY(79, 197.5); $pdf->Write(0, $nipKoordinator);

        // 2. JURUSAN    
        } elseif ($peminjaman->jenis_surat == 'jurusan') {
            // FORMAT 2x2 (Sarpras & Pemohon || Koordinator & Kakomli)
            if ($request->waka_sarpras) {
                $pdf->SetXY(35, 191); $pdf->Write(0, $request->waka_sarpras);
                $guru = \App\Models\User::where('name', $request->waka_sarpras)->first();
                if($guru) { $pdf->SetXY(43, 197.5); $pdf->Write(0, $guru->nisn_nip); }
            }
            
            // TTD Koordinator Teknisi (Kiri Bawah)
            $pdf->SetXY(35, 236); $pdf->Write(0, $namaKoordinator);
            $pdf->SetXY(43, 242); $pdf->Write(0, $nipKoordinator);

            if ($request->kakomli) {
                $pdf->SetXY(130, 236); $pdf->Write(0, $request->kakomli);
                $guru = \App\Models\User::where('name', $request->kakomli)->first();
                if($guru) { $pdf->SetXY(139, 242.5); $pdf->Write(0, $guru->nisn_nip); }
            }

        // 3. EKSKUL ATAU ORGANISASI
        } elseif ($peminjaman->jenis_surat == 'ekstrakurikuler') {
            // FORMAT 2x2 (Pembina & Pemohon || Kesiswaan & Sarpras)
            if ($request->pembina) {
                $pdf->SetXY(35, 191); $pdf->Write(0, $request->pembina);
                $guru = \App\Models\User::where('name', $request->pembina)->first();
                if($guru) { $pdf->SetXY(43, 197.5); $pdf->Write(0, $guru->nisn_nip); }
            }
            if ($request->waka_kesiswaan) {
                $pdf->SetXY(130, 236); $pdf->Write(0, $request->waka_kesiswaan);
                $guru = \App\Models\User::where('name', $request->waka_kesiswaan)->first();
                if($guru) { $pdf->SetXY(139, 242.5); $pdf->Write(0, $guru->nisn_nip); }
            }
            if ($request->waka_sarpras) {
                $pdf->SetXY(35, 239); $pdf->Write(0, $request->waka_sarpras);
                $guru = \App\Models\User::where('name', $request->waka_sarpras)->first();
                if($guru) { $pdf->SetXY(43, 244); $pdf->Write(0, $guru->nisn_nip); }
            }

        // 4. EKSKUL PINJAM KE JURUSAN
        } elseif ($peminjaman->jenis_surat == 'ekskul_ke_jurusan') {
            // FORMAT 2x2 (Pembina & Pemohon || Kesiswaan & Kakomli)
            if ($request->pembina) {
                $pdf->SetXY(35,191); $pdf->Write(0, $request->pembina);
                $guru = \App\Models\User::where('name', $request->pembina)->first();
                if($guru) { $pdf->SetXY(43, 197.5); $pdf->Write(0, $guru->nisn_nip); }
            }
            if ($request->waka_kesiswaan) {
                $pdf->SetXY(130, 236); $pdf->Write(0, $request->waka_kesiswaan);
                $guru = \App\Models\User::where('name', $request->waka_kesiswaan)->first();
                if($guru) { $pdf->SetXY(139, 242.5); $pdf->Write(0, $guru->nisn_nip); }
            }
            if ($request->kakomli) {
                $pdf->SetXY(35, 236); $pdf->Write(0, $request->kakomli);
                $guru = \App\Models\User::where('name', $request->kakomli)->first();
                if($guru) { $pdf->SetXY(43, 242); $pdf->Write(0, $guru->nisn_nip); }
            }
        }

        // --- C. KOTAK PETUGAS TEKNISI DI KANAN BAWAH (MultiCell - Rata Tengah & Bisa Enter) ---
        $pdf->SetFont('Times', '', 10); 
        $pdf->SetXY(131, 264);
        // MultiCell(Lebar, Tinggi Baris, Teks, Border, Align)
        $pdf->MultiCell(40, 4, Auth::user()->name, 0, 'C');


        // --- HALAMAN 2 : LAMPIRAN BARANG ---
        $tplId2 = $pdf->importPage(2);
        $pdf->AddPage();
        $pdf->useTemplate($tplId2);

        $pdf->SetFont('Times', '', 11);
        
        $y = 57;
        $no = 1;
        
        foreach ($peminjaman->barangs as $barang) {
            $pdf->SetXY(25, $y); 
            $pdf->Cell(8.4, 8, $no++, 1, 0, 'C');                
            $pdf->Cell(96.5, 8, $barang->nama_barang, 1, 0, 'L'); 
            $pdf->Cell(27.5, 8, $barang->pivot->jumlah . ' Unit', 1, 0, 'C'); 
            $pdf->Cell(34.8, 8, 'Baik', 1, 1, 'C');               
            $y += 8; 
        }

        // 3. Simpan File PDF Baru ke Storage Public
        $namaFile = 'Surat_Peminjaman_' . $peminjaman->id . '_' . time() . '.pdf';
        $pathSimpan = storage_path('app/public/' . $namaFile);
        $pdf->Output($pathSimpan, 'F');

        // 4. Update Database
        $peminjaman->update([
            'status' => 'bisa_diunduh',
            'teknisi_id' => Auth::id(),
            'file_pdf' => $namaFile
        ]);

        return redirect()->route('teknisi.peminjaman.index')->with('success', 'Surat berhasil diverifikasi & PDF bisa dicetak!');
    }

    // Fungsi Menolak Surat Pengajuan
    public function tolak(Request $request, $id)
    {
        // wajib mengisi alasan penolakan
        $request->validate([
            'pesan_penolakan' => 'required|string'
        ], [
            'pesan_penolakan.required' => 'Alasan penolakan wajib diisi!'
        ]);

        $peminjaman = \App\Models\Peminjaman::findOrFail($id);
        
        // Update database: Ubah status dan simpan pesan penolakan
        $peminjaman->update([
            'status' => 'ditolak',
            'pesan_penolakan' => $request->pesan_penolakan,
            'teknisi_id' => \Illuminate\Support\Facades\Auth::id() // siapa Teknisi yang menolak
        ]);

        return redirect()->route('teknisi.peminjaman.index')->with('success', 'Pengajuan surat berhasil DITOLAK!');
    }

}