<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Detail Verifikasi | SMKN 1 Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white">
                    <i class="fas fa-file-signature text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Verifikasi Pengajuan</h1>
                    <p class="text-xs text-slate-400">Pastikan ketersediaan barang sebelum mencetak PDF</p>
                </div>
            </div>
            <a href="{{ route('teknisi.peminjaman.index') }}" class="text-slate-500 hover:text-blue-600 font-medium text-sm transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Antrean
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-user-circle text-blue-500"></i> Informasi Peminjam
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-1">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nama Lengkap</p>
                            <p class="text-slate-700 font-semibold">{{ $peminjaman->user->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">NISN / NIP</p>
                            <p class="text-slate-700 font-semibold">{{ $peminjaman->user->nisn_nip }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Unit Kerja / Kelas</p>
                            <p class="text-slate-700 font-semibold">{{ $peminjaman->user->kelas_unit_kerja }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Jenis Surat</p>
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">
                                {{ strtoupper(str_replace('_', ' ', $peminjaman->jenis_surat)) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                        <div class="space-y-1">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Waktu Peminjaman</p>
                            <p class="text-sm text-slate-700 font-semibold"><i class="far fa-calendar-alt text-blue-500 mr-1"></i> {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y') }} <span class="text-slate-400 mx-1">s/d</span> {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="space-y-1 md:col-span-1">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Alasan Peminjaman</p>
                            <p class="text-sm text-slate-700 font-semibold italic">"{{ $peminjaman->alasan }}"</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                    <div class="p-8 pb-4 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                            <i class="fas fa-boxes text-blue-500"></i> Rincian Barang yang Diajukan
                        </h2>
                    </div>
                    
                    <div class="overflow-x-auto px-8 pb-4">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="py-4 text-[10px] uppercase font-bold text-slate-400">No</th>
                                    <th class="py-4 text-[10px] uppercase font-bold text-slate-400">Nama Barang</th>
                                    <th class="py-4 text-[10px] uppercase font-bold text-slate-400 text-center">Jumlah</th>
                                    <th class="py-4 text-[10px] uppercase font-bold text-slate-400 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($peminjaman->barangs as $index => $barang)
                                <tr>
                                    <td class="py-4 text-sm font-medium text-slate-500">{{ $index + 1 }}</td>
                                    <td class="py-4 text-sm font-bold text-slate-700">{{ $barang->nama_barang }}</td>
                                    <td class="py-4 text-sm font-semibold text-slate-600 text-center">{{ $barang->pivot->jumlah }} Unit</td>
                                    <td class="py-4 text-right">
                                        <form action="{{ route('teknisi.peminjaman.hapus_barang', ['id' => $peminjaman->id, 'barang_id' => $barang->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus barang ini dari pengajuan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-xl transition-all" title="Hapus Barang jika kosong">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-slate-50 p-6 border-t border-slate-100">
                        <form action="{{ route('teknisi.peminjaman.tambah_barang', $peminjaman->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                            @csrf
                            <div class="flex-1 w-full">
                                <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-2 block">Pilih Barang Pengganti / Tambahan</label>
                                <select name="barang_id" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-32">
                                <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-2 block">Jmlh Unit</label>
                                <input type="number" name="jumlah" min="1" required placeholder="1" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <button type="submit" class="w-full md:w-auto bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-warehouse text-blue-500"></i> Info Ketersediaan Barang
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-64 overflow-y-auto pr-2">
                        @foreach($barangs as $b)
                        <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl flex justify-between items-center group hover:border-blue-200 transition-colors">
                            <span class="text-sm font-semibold text-slate-700 truncate mr-2">{{ $b->nama_barang }}</span>
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-lg">{{ $b->stok_tersedia }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden sticky top-28">
                    <div class="bg-blue-600 p-6 text-white">
                        <h2 class="text-lg font-bold">Proses Verifikasi</h2>
                        <p class="text-xs text-blue-100 opacity-80 mt-1">Lengkapi nama Pejabat pengesah untuk dicetak pada surat.</p>
                    </div>

                    <form action="{{ route('teknisi.peminjaman.verifikasi', $peminjaman->id) }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        
                        @if(in_array($peminjaman->jenis_surat, ['ekstrakurikuler', 'ekskul_ke_jurusan']))
                        <div class="space-y-2">
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pilih Nama Pembina</label>
                            <select name="pembina" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="">-- Cari Nama Guru --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->name }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if(in_array($peminjaman->jenis_surat, ['jurusan', 'ekskul_ke_jurusan']))
                        <div class="space-y-2">
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Kepala Kompetensi Keahlian</label>
                            <select name="kakomli" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="">-- Pilih Kakomli --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->name }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if(in_array($peminjaman->jenis_surat, ['ekstrakurikuler', 'ekskul_ke_jurusan']))
                        <div class="space-y-2">
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Waka Kesiswaan</label>
                            <select name="waka_kesiswaan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="">-- Pilih Waka Kesiswaan --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->name }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if(in_array($peminjaman->jenis_surat, ['guru_tendi', 'jurusan', 'ekstrakurikuler']))
                        <div class="space-y-2">
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Waka Sarana Prasarana</label>
                            <select name="waka_sarpras" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="">-- Pilih Waka Sarpras --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->name }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                       <div class="pt-4 space-y-3">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center gap-3 group">
                                <i class="fas fa-print group-hover:scale-110 transition-transform"></i>
                                Verifikasi & Cetak PDF
                            </button>
                            
                            <button type="button" onclick="document.getElementById('modalTolak').classList.remove('hidden')" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3.5 rounded-2xl border border-red-200 transition-all flex items-center justify-center gap-3">
                                <i class="fas fa-times-circle"></i> Tolak Pengajuan
                            </button>

                            <p class="text-[10px] text-center text-slate-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Verifikasi untuk mencetak PDF, atau Tolak jika pengajuan bermasalah.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

   <div id="modalTolak" class="hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center px-4">
            <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
                <h3 class="text-xl font-bold text-slate-800 mb-2">Tolak Pengajuan Surat</h3>
                <p class="text-sm text-slate-500 mb-6">Silakan tuliskan alasan kenapa pengajuan surat ini ditolak agar User dapat mengetahuinya.</p>
                
                <form action="{{ route('teknisi.peminjaman.tolak', $peminjaman->id) }}" method="POST">
                    @csrf
                    <textarea name="pesan_penolakan" rows="4" required placeholder="Contoh: Maaf, Laptop ROG sedang rusak dan masuk masa perbaikan..." class="w-full border border-slate-200 rounded-xl p-4 text-sm focus:ring-2 focus:ring-red-500 outline-none resize-none mb-6"></textarea>
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('modalTolak').classList.add('hidden')" class="w-full px-4 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="w-full px-4 py-3 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all">
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>