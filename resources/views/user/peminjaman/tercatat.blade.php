<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Form Pengajuan Surat | Teknisi SMKN 1 Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; }
        .theme-crystal { 
            background: radial-gradient(circle at top right, #1e3a8a 0%, #172554 40%, #0f172a 100%); 
            min-height: 100vh;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }
        /* Custom Scrollbar untuk daftar barang */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="theme-crystal py-10 px-4 min-h-screen flex items-center justify-center relative">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-500/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="absolute top-6 left-6 z-40">
        <a href="{{ route('user.peminjaman.index') }}" class="flex items-center gap-2 text-blue-200 hover:text-white transition-colors font-medium bg-white/5 px-4 py-2 rounded-full border border-white/10 hover:bg-white/10 shadow-lg">
            <i class="fas fa-arrow-left"></i> Kembali ke Pilihan
        </a>
    </div>

    <div class="glass-panel w-full max-w-5xl rounded-3xl overflow-hidden relative z-10 flex flex-col md:flex-row">
        
        <div class="bg-indigo-600 md:w-1/3 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-2xl mb-6 border border-white/20 shadow-inner">
                    <i class="fas fa-id-card-clip"></i>
                </div>
                <h2 class="text-2xl font-bold mb-1">Data Peminjam</h2>
                <p class="text-indigo-200 text-sm mb-8">Data di bawah ini ditarik otomatis dari akun Anda dan akan dicetak pada surat PDF.</p>

                <div class="space-y-5">
                    <div>
                        <p class="text-indigo-300 text-xs font-bold uppercase tracking-wider mb-1">Nama Lengkap</p>
                        <p class="font-semibold text-lg">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-indigo-300 text-xs font-bold uppercase tracking-wider mb-1">NISN / NIP</p>
                        <p class="font-semibold text-lg">{{ auth()->user()->nisn_nip ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-indigo-300 text-xs font-bold uppercase tracking-wider mb-1">Kelas / Unit Kerja</p>
                        <p class="font-semibold text-lg">{{ auth()->user()->kelas_unit_kerja ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-indigo-300 text-xs font-bold uppercase tracking-wider mb-1">Nomor HP</p>
                        <p class="font-semibold text-lg">{{ auth()->user()->no_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 mt-12 pt-6 border-t border-white/20 flex items-center gap-3">
                <i class="fas fa-shield-check text-emerald-400 text-2xl"></i>
                <p class="text-xs text-indigo-200 leading-tight">Data ini telah diverifikasi oleh sistem secara otomatis.</p>
            </div>
        </div>

        <div class="p-8 md:p-10 md:w-2/3 bg-white/95">
            <div class="mb-8 border-b border-slate-100 pb-4 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800">Form Pengajuan Surat</h2>
                    <p class="text-slate-500 font-medium text-sm mt-1">Isi detail di bawah ini dengan lengkap dan benar.</p>
                </div>
                <span class="bg-blue-100 text-blue-700 font-bold text-xs px-3 py-1 rounded-lg">Barang Tercatat</span>
            </div>

            <form action="{{ route('user.peminjaman.tercatat.store') }}" method="POST" id="formPeminjaman">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-slate-700 text-sm font-bold mb-2">Jenis Format Surat <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="jenis_surat" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm appearance-none font-medium cursor-pointer">
                                <option value="" disabled selected>-- Pilih Jenis Surat / Peminjam --</option>
                                <option value="jurusan">Atas Nama Jurusan</option>
                                <option value="ekstrakurikuler">Atas Nama Ekstrakurikuler / Organisasi</option>
                                <option value="ekskul_ke_jurusan">Ekstrakurikuler Pinjam ke Jurusan</option>
                                <option value="guru_tendi">Atas Nama Guru / Tenaga Pendidik</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Tanggal Peminjaman <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_pinjam" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Rencana Dikembalikan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_kembali" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-slate-700 text-sm font-bold mb-2">Alasan Peminjaman / Kegiatan <span class="text-rose-500">*</span></label>
                        <textarea name="alasan" rows="3" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm resize-none" placeholder="Contoh: Digunakan untuk liputan kegiatan LDKS Osis di aula..."></textarea>
                    </div>

                    <div class="md:col-span-2">
                       <label class="flex text-slate-700 text-sm font-bold mb-2 justify-between items-center">
                            <span>Pilih Alat dan Barang<span class="text-rose-500">*</span></span>
                            <span class="text-xs font-normal text-slate-500 bg-slate-100 px-2 py-1 rounded-md">Minimal 1 barang</span>
                        </label>
                        
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 max-h-52 overflow-y-auto custom-scrollbar">
                            @if(isset($barangTercatat) && $barangTercatat->count() > 0)
                                <div class="space-y-3">
                                    @foreach($barangTercatat as $barang)
                                    <div class="flex items-center gap-3 p-3 bg-white border border-slate-100 rounded-lg hover:border-blue-300 transition-all shadow-sm">
                                        <input type="checkbox" name="barang_id[]" value="{{ $barang->id }}" id="brg_{{ $barang->id }}" class="w-5 h-5 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 cursor-pointer" onchange="toggleJumlah({{ $barang->id }})">
                                        
                                        <label for="brg_{{ $barang->id }}" class="flex-1 cursor-pointer">
                                            <p class="text-sm font-bold text-slate-800">{{ $barang->nama_barang }}</p>
                                            <p class="text-xs text-slate-500">Tersedia: <span class="font-bold text-emerald-600">{{ $barang->stok_tersedia }} Unit</span></p>
                                        </label>

                                        <div id="wrap_jumlah_{{ $barang->id }}" class="hidden items-center gap-2">
                                            <span class="text-xs font-bold text-slate-500">Jml:</span>
                                            <input type="number" name="jumlah[{{ $barang->id }}]" id="jumlah_{{ $barang->id }}" min="1" max="{{ $barang->stok_tersedia }}" value="1" class="w-16 px-2 py-1 border border-blue-200 rounded-lg text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" disabled>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Tidak ada barang tercatat yang tersedia saat ini.</p>
                                    <p class="text-xs text-slate-500 mt-1">Silakan cek kembali nanti atau hubungi Teknisi.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-blue-500/30 flex items-center gap-3 hover:-translate-y-1">
                        Kirim Pengajuan Surat <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>

        </div>
    </div>
<script>
        // untuk memunculkan input jumlah saat dicentang
        function toggleJumlah(id) {
            const checkbox = document.getElementById('brg_' + id);
            const wrapJumlah = document.getElementById('wrap_jumlah_' + id);
            const inputJumlah = document.getElementById('jumlah_' + id);

            if (checkbox.checked) {
                wrapJumlah.classList.remove('hidden');
                wrapJumlah.classList.add('flex');
                inputJumlah.disabled = false; // Aktifkan input agar datanya terkirim
            } else {
                wrapJumlah.classList.add('hidden');
                wrapJumlah.classList.remove('flex');
                inputJumlah.disabled = true; // Matikan input
            }
        }
    </script>
</body>
</html>