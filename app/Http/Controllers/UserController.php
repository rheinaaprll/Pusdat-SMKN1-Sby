<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Hash; 
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Auth;
use App\Imports\RfidImport;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use App\Models\User; // Memanggil model User agar bisa mengambil data dari database

class UserController extends Controller
{
    // Fungsi ini bertugas mengambil data dan menampilkannya ke halaman web
   public function index(Request $request)
    {
        $search = $request->input('search');
        $querySiswa = User::where('role', 'siswa');
        $queryGuru = User::where('role', 'guru');
        $queryTendik = User::where('role', 'tenaga_pendidik');
        $queryPengelola = User::whereIn('role', ['teknisi', 'koordinator']);

        // 3. Filter untuk pencarian
        if ($search) {
            $querySiswa->where('name', 'like', "%{$search}%");
            $queryGuru->where('name', 'like', "%{$search}%");
            $queryTendik->where('name', 'like', "%{$search}%");
            $queryPengelola->where('name', 'like', "%{$search}%");
        }

        // 4. PENGURUTAN  
        $siswa = $querySiswa->orderBy('kelas_unit_kerja', 'asc')->orderBy('name', 'asc')->paginate(10);
        $guru = $queryGuru->orderBy('name', 'asc')->paginate(10);
        $tendik = $queryTendik->orderBy('name', 'asc')->paginate(10);

        // C. Pengelola: 1. Diri Sendiri -> 2. Koord -> 3. Teknisi -> 4. Abjad Nama
       $loggedInId = Auth::id();
        $pengelolaRaw = $queryPengelola->get();
        
        $pengelola = $pengelolaRaw->sortBy(function($user) use ($loggedInId) {
            $rank = 4; // Ranking default
            if ($user->id == $loggedInId) {
                $rank = 1; // Prioritas Tertinggi 
            } elseif ($user->role == 'koordinator') {
                $rank = 2; 
            } elseif ($user->role == 'teknisi') {
                $rank = 3; 
            }
            // Menggabungkan Rank dan Nama agar jika rolenya sama, diurutkan abjad
            return $rank . '-' . $user->name;
        })->values(); 

        return view('teknisi.users.index', compact('siswa', 'guru', 'tendik', 'pengelola'));
    }

    // menampilkan halaman form tambah data
    public function create()
    {
        return view('teknisi.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn_nip' => 'required|string|unique:users,nisn_nip', // unique: tidak boleh ada NIP/NISN kembar
            'role' => 'required|in:siswa,guru,tenaga_pendidik,koordinator,teknisi',
            'no_hp' => 'nullable|string',
            'kelas_unit_kerja' => 'required|string'
        ]);

        $passwordAcak = Str::random(8);
        User::create([
            'name' => $request->name,
            'nisn_nip' => $request->nisn_nip,  
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'kelas_unit_kerja' => $request->kelas_unit_kerja,
            'password' => Hash::make($passwordAcak), 
        ]);

        return redirect()->route('teknisi.users.index')
            ->with('success', 'User baru berhasil ditambahkan! Passwordnya adalah: ' . $passwordAcak);
    }

    // Fungsi untuk menghapus data user dari database
    public function destroy($id)
    {
        $user = User::findOrFail($id); // Mencari data user berdasarkan ID yang diklik
        $namaUser = $user->name; // Menyimpan nama user untuk ditampilkan di pesan sukses 
        $user->delete();
        return redirect()->route('teknisi.users.index')
            ->with('success', 'Data user atas nama ' . $namaUser . ' berhasil dihapus secara permanen!');
    }

    // Fungsi untuk mereset password user
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $passwordBaru = Str::random(8);
        $user->update([
            'password' => Hash::make($passwordBaru)
        ]);

        return redirect()->back()
            ->with('success', 'Password milik ' . $user->name . ' berhasil direset! Password barunya adalah: ' . $passwordBaru);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('teknisi.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data 
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn_nip' => 'required|string|unique:users,nisn_nip,' . $id, 
            'role' => 'required|in:siswa,guru,tenaga_pendidik,koordinator,teknisi',
            'no_hp' => 'nullable|string',
            'kelas_unit_kerja' => 'required|string'
        ]);

        // Menyimpan perubahan (password tidak ikut diubah)
        $user->update([
            'name' => $request->name,
            'nisn_nip' => $request->nisn_nip,
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'kelas_unit_kerja' => $request->kelas_unit_kerja,
            'rfid' => $request->rfid,
        ]);

        return redirect()->route('teknisi.users.index')
            ->with('success', 'Data milik ' . $user->name . ' berhasil diperbarui!');
    }

    // Fungsi untuk memproses file Excel yang diupload
    public function importExcel(Request $request)
    {
        set_time_limit(0);

        //  Validasi file excel
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $import = new UsersImport();
        Excel::import($import, $request->file('file_excel'));

        $akunBaru = $import->akunBaru;
        if (count($akunBaru) > 0) {
            return redirect()->route('teknisi.users.index')
                ->with('success', count($akunBaru) . ' Data user berhasil diimport!')
                ->with('akunBaru', $akunBaru);
        }

        return redirect()->route('teknisi.users.index')
            ->with('success', 'Import selesai, tapi tidak ada data baru (mungkin NISN/NIP sudah terdaftar semua).');
    }

    // Fungsi Kenaikan Kelas
    public function formKenaikan()
    {
        $daftarKelas = User::where('role', 'siswa')
            ->select('kelas_unit_kerja')
            ->distinct()
            ->orderBy('kelas_unit_kerja', 'asc')
            ->pluck('kelas_unit_kerja');

        return view('teknisi.users.kenaikan_kelas', compact('daftarKelas'));
    }

    // Fungsi memproses perpindahan data kelas secara massal
    public function prosesKenaikan(Request $request)
    {
        $request->validate([
            'kelas_lama' => 'required|string',
            'kelas_baru' => 'required|string'
        ]);

        if ($request->kelas_lama == $request->kelas_baru) {
            return redirect()->back()->with('error', 'Kelas tujuan tidak boleh sama dengan kelas asal!');
        }

        $jumlahSiswa = User::where('role', 'siswa')
            ->where('kelas_unit_kerja', $request->kelas_lama)
            ->update(['kelas_unit_kerja' => $request->kelas_baru]);
        return redirect()->route('teknisi.users.index')
            ->with('success', 'Berhasil! Sebanyak ' . $jumlahSiswa . ' siswa dari kelas ' . $request->kelas_lama . ' telah dinaikkan/dipindah ke ' . $request->kelas_baru . '.');
    }

    // Fungsi khusus Koordinator untuk Import RFID
    public function importRfid(Request $request)
    {
        $request->validate([
            'file_rfid' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $import = new RfidImport();
        Excel::import($import, $request->file('file_rfid'));

        return redirect()->route('teknisi.users.index')
            ->with('success', 'Berhasil! Kartu RFID untuk ' . $import->jumlahBerhasil . ' pengguna telah diupdate.');
    }

    public function unduhPasswordPdf()
    {
        $akunBaru = session('akunBaru');
        if (!$akunBaru) return back()->with('error', 'Data password sudah tidak tersedia.');

        $pdf = new Fpdi();
        
        // --- HALAMAN 1  
        $pdf->AddPage();
        $templatePath = storage_path('app/template/template_laporan.pdf');
        $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx, 0, 0, 210);  

        // Font & Judul
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetY(50);  
        $pdf->Cell(0, 10, 'DAFTAR AKUN & PASSWORD PENGGUNA BARU', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Dicetak pada: ' . now()->format('d/m/Y H:i'), 0, 1, 'C');
        $pdf->Ln(5);

        // Header Tabel
        $pdf->SetFillColor(180, 198, 231);  
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 8, 'NO', 1, 0, 'C', true);
        $pdf->Cell(80, 8, 'NAMA LENGKAP', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'NISN / NIP / USERNAME', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'PASSWORD DEFAULT', 1, 1, 'C', true);

        // Isi Tabel
        $pdf->SetFont('Arial', '', 10);
        foreach ($akunBaru as $index => $akun) {
            // tambah halaman baru
            if ($pdf->GetY() > 270) {
                $pdf->AddPage(); 
                $pdf->SetY(20); 
                
                // Cetak ulang header tabel di halaman baru agar rapi
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(10, 8, 'NO', 1, 0, 'C', true);
                $pdf->Cell(80, 8, 'NAMA LENGKAP', 1, 0, 'C', true);
                $pdf->Cell(50, 8, 'NISN / NIP / USERNAME', 1, 0, 'C', true);
                $pdf->Cell(50, 8, 'PASSWORD DEFAULT', 1, 1, 'C', true);
                $pdf->SetFont('Arial', '', 10);
            }

            $pdf->Cell(10, 8, $index + 1, 1, 0, 'C');
            $pdf->Cell(80, 8, strtoupper($akun['nama']), 1, 0, 'L');
            $pdf->Cell(50, 8, $akun['nisn_nip'], 1, 0, 'C');
            $pdf->SetFont('Courier', 'B', 10); // Password  
            $pdf->Cell(50, 8, $akun['password'], 1, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
        }

        return response($pdf->Output('S', 'Daftar_Password.pdf'), 200)
            ->header('Content-Type', 'application/pdf');
    }

}