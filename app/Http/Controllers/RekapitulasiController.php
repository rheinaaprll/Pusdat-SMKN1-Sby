<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use setasign\Fpdi\Fpdi;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barangs', 'teknisi']);

        // Fitur Filter: Berdasarkan Status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        // Fitur Filter: Berdasarkan Jenis Surat
        if ($request->has('jenis_surat') && $request->jenis_surat != 'semua') {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        // Ambil data terbaru di atas
        $riwayats = $query->orderBy('updated_at', 'desc')->paginate(15); // Gunakan paginate agar rapi

        return view('koordinator.rekapitulasi.index', compact('riwayats'));
    }

    // Fungsi Mengunduh Laporan  
    public function unduhLaporan(Request $request)
    {
        $query = \App\Models\Peminjaman::with(['user', 'barangs']);

        // Filter tanggal (Harian/Mingguan/Bulanan)
        if ($request->has('rentang')) {
            $rentang = $request->rentang;
            if ($rentang == 'harian') {
                $query->whereDate('created_at', \Carbon\Carbon::today());
            } elseif ($rentang == 'mingguan') {
                $query->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($rentang == 'bulanan') {
                $query->whereMonth('created_at', \Carbon\Carbon::now()->month);
            }
        }

        $dataLaporan = $query->orderBy('created_at', 'desc')->get();
        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);  
        $templatePath = storage_path('app/template/template_laporan.pdf');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Gagal! File template_laporan.pdf tidak ditemukan di folder storage/app/template/');
        }

        // Import halaman pertama dari template
        $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1);

        // Fungsi untuk menggambar Header Tabel berulang-ulang
        $drawTableHeader = function($pdf) {
            $pdf->SetFont('Times', 'B', 9); //  
            $pdf->SetFillColor(241, 245, 249);
            $pdf->Cell(10, 8, 'NO', 1, 0, 'C', true);
            $pdf->Cell(25, 8, 'TANGGAL', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'PEMINJAM', 1, 0, 'C', true);
            $pdf->Cell(65, 8, 'RINCIAN BARANG', 1, 0, 'C', true);
            $pdf->Cell(20, 8, 'JENIS', 1, 0, 'C', true);
            $pdf->Cell(20, 8, 'STATUS', 1, 1, 'C', true);
        };

        // HALAMAN PERTAMA PAKAI KOP SURAT
        $pdf->AddPage();
        $pdf->useTemplate($templateId, 0, 0, 210); 

        // Set Titik Awal 
        $pdf->SetY(55); 

        // Judul Laporan  
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Cell(0, 8, 'LAPORAN REKAPITULASI PEMINJAMAN BARANG', 0, 1, 'C');
        
        $pdf->SetFont('Times', '', 10);
        // Zona WIB
        $waktuCetak = \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';
        $pdf->Cell(0, 5, 'Dicetak pada: ' . $waktuCetak, 0, 1, 'C');
        $pdf->Ln(10);

        // Gambar Header Tabel
        $drawTableHeader($pdf);

        // Isi Tabel Data 
        $pdf->SetFont('Times', '', 8);
        $no = 1;

        foreach ($dataLaporan as $r) {
            
            if ($pdf->GetY() > 260) {
                $pdf->AddPage();
            
                $pdf->SetY(15);
                $drawTableHeader($pdf); 
                $pdf->SetFont('Times', '', 8); 
            }

            $tanggal = $r->created_at->format('d/m/Y');
            $nama = substr($r->user->name, 0, 28);
            $jenis = $r->jenis_surat == 'tanpa_surat' ? 'Umum' : 'Surat';
            $status = strtoupper($r->status);

            $barangArray = [];
            foreach($r->barangs as $b) {
                $barangArray[] = $b->nama_barang . ' (' . $b->pivot->jumlah . ')';
            }
            $rincian = substr(implode(', ', $barangArray), 0, 45); 

            // Cetak Baris
            $pdf->Cell(10, 7, $no++, 1, 0, 'C');
            $pdf->Cell(25, 7, $tanggal, 1, 0, 'C');
            $pdf->Cell(50, 7, $nama, 1, 0, 'L');
            $pdf->Cell(65, 7, $rincian, 1, 0, 'L');
            $pdf->Cell(20, 7, $jenis, 1, 0, 'C');
            $pdf->Cell(20, 7, $status, 1, 1, 'C');
        }

        // Tanda Tangan Koordinator di akhir laporan
        $pdf->Ln(10);
        
       
        if ($pdf->GetY() > 240) {
            $pdf->AddPage();

            $pdf->SetY(20);
        }
        
        $pdf->SetFont('Times', '', 10); 
        $pdf->Cell(130, 5, '', 0, 0); 
        $pdf->Cell(60, 5, 'Surabaya, ' . date('d F Y'), 0, 1, 'C');
        $pdf->Cell(130, 5, '', 0, 0);
        $pdf->Cell(60, 5, 'Koordinator Teknisi', 0, 1, 'C');
        
        $pdf->Ln(20); 
        
        $pdf->SetFont('Times', 'B', 10);  
        $pdf->Cell(130, 5, '', 0, 0);
        $pdf->Cell(60, 5, \Illuminate\Support\Facades\Auth::user()->name, 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);  
        $pdf->Cell(130, 5, '', 0, 0);
        $pdf->Cell(60, 5, 'NIP. ' . \Illuminate\Support\Facades\Auth::user()->nisn_nip, 0, 1, 'C');

        $pdf->Output('I', 'Laporan_Rekapitulasi_' . date('Ymd') . '.pdf');
        exit;
    }

    // 3. Fungsi Hapus Riwayat (Hanya Koordinator)
    public function destroy($id)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'koordinator') {
            abort(403, 'Akses Ditolak. Hanya Koordinator yang dapat menghapus riwayat.');
        }

        $riwayat = Peminjaman::findOrFail($id);
        $riwayat->barangs()->detach(); 
        $riwayat->delete();

        return back()->with('success', 'Riwayat berhasil dihapus dari sistem secara permanen.');
    }
}