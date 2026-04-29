<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Pengembalian Barang | Teknisi SMKN 1 Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen pb-12">

    <div class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-500 p-2.5 rounded-xl text-white shadow-lg shadow-emerald-200">
                    <i class="fas fa-undo-alt text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Pengembalian Barang</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar inventaris umum yang sedang Anda bawa.</p>
                </div>
            </div>
            <a href="/dashboard" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-md shadow-slate-200">
                <i class="fas fa-rocket"></i> Tata Surya
            </a>
        </div>
    </div>

    <main class="max-w-5xl mx-auto px-6 mt-10">

        @if($sopPengembalian)
        <div id="sopModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white w-11/12 max-w-2xl rounded-2xl overflow-hidden shadow-2xl flex flex-col max-h-[80vh] transform transition-all duration-300 scale-100">
                <div class="bg-emerald-600 p-6 flex items-center gap-4 text-white">
                    <div class="bg-white/20 p-3 rounded-xl shrink-0">
                        <i class="fas fa-file-contract text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight">SOP Pengembalian Barang</h2>
                        <p class="text-sm text-emerald-100 mt-1">Wajib dibaca & dipahami sebelum mengembalikan.</p>
                    </div>
                </div>
                
                <div class="p-6 overflow-y-auto text-slate-700 text-sm leading-relaxed prose prose-slate max-w-none">
                    {!! nl2br(e($sopPengembalian->isi_sop ?? $sopPengembalian->deskripsi)) !!}
                </div>

                <div class="p-5 border-t border-slate-100 bg-slate-50 flex justify-end">
                    <button onclick="closeSopModal()" class="bg-emerald-600 hover:bg-green-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-md shadow-indigo-200 hover:-translate-y-0.5">
                        <i class="fas fa-check"></i> Saya Mengerti & Setuju
                    </button>
                </div>
            </div>
        </div>

        <script>
            function closeSopModal() {
                const modal = document.getElementById('sopModal');
                const kontenUtama = document.getElementById('kontenUtama');
                
                modal.classList.add('opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    if(kontenUtama) {
                        kontenUtama.classList.remove('hidden');
                    }
                }, 300);
            }
        </script>
        @endif

        <div id="kontenUtama" class="{{ $sopPengembalian ? 'hidden' : '' }}">
        
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-2xl text-sm font-bold border border-emerald-200 flex items-center gap-3 mb-8 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if($peminjamans->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($peminjamans as $p)
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-lg transition-all relative overflow-hidden flex flex-col">
                            <div class="absolute top-0 left-0 right-0 h-1.5 bg-emerald-500"></div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center text-xl">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-amber-100 flex items-center gap-1.5 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Sedang Dipinjam
                                </span>
                            </div>
                            <div class="flex-1 mb-6">
                                @foreach($p->barangs as $barang)
                                    <h3 class="text-lg font-bold text-slate-800 mb-1 leading-tight">{{ $barang->nama_barang }}</h3>
                                    <p class="text-sm font-semibold text-slate-500 mb-3"><i class="fas fa-hashtag mr-1 text-slate-300"></i> Jumlah: {{ $barang->pivot->jumlah }} Unit</p>
                                @endforeach
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 mt-2">
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-1">Alasan Peminjaman:</p>
                                    <p class="text-xs text-slate-600 italic">"{{ $p->alasan }}"</p>
                                </div>
                                <p class="text-[10px] font-semibold text-slate-400 mt-4"><i class="far fa-clock mr-1"></i> Dipinjam sejak: {{ $p->created_at->diffForHumans() }}</p>
                            </div>
                            <form action="{{ route('user.pengembalian.proses', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan barang ini sekarang?');">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-200 hover:border-emerald-500 font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 group">
                                    <i class="fas fa-undo-alt group-hover:-rotate-180 transition-transform duration-500"></i> Kembalikan Barang
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-16 text-center border border-slate-200 shadow-sm mt-8 max-w-2xl mx-auto">
                    <div class="w-24 h-24 bg-emerald-50 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border border-emerald-100">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Bebas Tanggungan!</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Anda tidak memiliki barang umum yang sedang dipinjam saat ini. Semua inventaris telah dikembalikan dengan baik.</p>
                </div>
            @endif

        </div> </main>

</body>
</html>