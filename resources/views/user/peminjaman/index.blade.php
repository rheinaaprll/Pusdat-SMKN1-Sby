<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Pilih Jenis Peminjaman | Teknisi SMKN 1 Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; }
        .theme-crystal { 
            background: radial-gradient(circle at top right, #1e3a8a 0%, #172554 40%, #0f172a 100%); 
            min-height: 100vh; 
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
            border-color: rgba(147, 197, 253, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), inset 0 0 20px rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="theme-crystal text-slate-200">

    <div class="absolute top-6 left-6 z-40">
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 text-blue-200 hover:text-white transition-colors font-medium bg-white/5 px-4 py-2 rounded-full border border-white/10 hover:bg-white/10">
            <i class="fas fa-arrow-left"></i> Kembali ke Tata Surya
        </a>
    </div>

    <div id="sopModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 transition-all duration-500">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-transform duration-500 scale-100" id="sopBox">
            <div class="bg-indigo-600 px-8 py-6 flex items-center justify-between border-b border-indigo-700">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-xl">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">SOP Peminjaman Barang</h3>
                        <p class="text-indigo-200 text-sm">Wajib dibaca & dipahami sebelum meminjam.</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8 max-h-[50vh] overflow-y-auto text-slate-700 leading-relaxed whitespace-pre-line">
                @if($sop && $sop->isi_sop)
                    {{ $sop->isi_sop }}
                @else
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-info-circle text-4xl mb-3"></i>
                        <p>Belum ada SOP yang diatur oleh Teknisi.</p>
                    </div>
                @endif
            </div>
            
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button onclick="closeSop()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-check"></i> Saya Mengerti & Setuju
                </button>
            </div>
        </div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 relative z-10 pt-20 pb-10">
        
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3">Pilih Kategori Barang</h1>
            <p class="text-blue-200 font-medium max-w-lg mx-auto">Sesuaikan dengan jenis barang yang ingin Anda pinjam hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl w-full">
            
            <a href="{{ route('user.peminjaman.tercatat') }}" class="glass-card rounded-3xl p-8 group relative overflow-hidden"> 
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-400/30 transition-all"></div>
                
                <div class="w-20 h-20 bg-linear-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center text-white text-4xl mb-6 shadow-lg shadow-blue-500/30">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Barang Tercatat</h2>
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-blue-500/20 text-blue-300 text-xs font-bold px-3 py-1 rounded-full border border-blue-500/30">Wajib Surat Resmi</span>
                </div>
                <p class="text-slate-300 text-sm leading-relaxed mb-8">Peminjaman untuk alat berat, kamera, atau barang inventaris bernilai tinggi. Sistem akan otomatis mencetak surat PDF setelah Anda mengisi form.</p>
                
                <div class="flex items-center text-blue-400 font-bold group-hover:text-blue-300 transition-colors">
                    Mulai Ajukan Surat <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <a href="{{ route('user.peminjaman.umum') }}" class="glass-card rounded-3xl p-8 group relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl group-hover:bg-emerald-400/30 transition-all"></div>
                
                <div class="w-20 h-20 bg-linear-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center text-white text-4xl mb-6 shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-tools"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Barang Umum</h2>
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-emerald-500/20 text-emerald-300 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/30">Langsung Ambil</span>
                    <span class="bg-amber-500/20 text-amber-300 text-xs font-bold px-3 py-1 rounded-full border border-amber-500/30">Harian</span>
                </div>
                <p class="text-slate-300 text-sm leading-relaxed mb-8">Peminjaman perkakas ringan seperti kabel, LCD projector, atau HDMI to VGA converters untuk keperluan praktik harian. Cukup pilih barang dan langsung pergi ke ruang teknisi.</p>
                
                <div class="flex items-center text-emerald-400 font-bold group-hover:text-emerald-300 transition-colors">
                    Lihat Daftar Barang <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

        </div>
    </div>

    <script>
        // Animasi Menutup SOP
        function closeSop() {
            const modal = document.getElementById('sopModal');
            const box = document.getElementById('sopBox');
            
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            modal.classList.add('opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 500);
        }
    </script>
</body>
</html>