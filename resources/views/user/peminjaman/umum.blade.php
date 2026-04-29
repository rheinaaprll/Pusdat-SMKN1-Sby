<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Katalog Barang Umum | Teknisi SMKN 1 Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen pb-12">

    <div class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Katalog Barang Umum</h1>
                <p class="text-xs text-slate-500 mt-0.5">Peminjaman langsung tanpa surat resmi.</p>
            </div>
            <a href="{{ route('user.peminjaman.index') }}" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-md shadow-slate-200">
                <i class="fas fa-rocket"></i> Kembali ke Pilihan
            </a>
        </div>
    </div>

    <main class="max-w-5xl mx-auto px-6 mt-10">
        
        @if(session('error'))
            <div class="bg-red-50 text-red-600 px-4 py-3 rounded-2xl text-sm font-bold border border-red-200 flex items-center gap-3 mb-8 shadow-sm">
                <i class="fas fa-exclamation-triangle text-lg"></i> {{ session('error') }}
            </div>
        @endif

        @if($barangs->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($barangs as $b)
                    <div onclick="bukaModal({{ $b->id }}, '{{ $b->nama_barang }}', {{ $b->stok_tersedia }})" class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer group flex flex-col items-center text-center relative overflow-hidden">
                        
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-linear-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-toolbox"></i>
                        </div>
                        
                        <h3 class="text-slate-800 font-bold mb-1 line-clamp-2">{{ $b->nama_barang }}</h3>
                        <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-4">{{ $b->kategori ?? 'Umum' }}</p>
                        
                        <div class="mt-auto bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-200 flex items-center gap-2">
                            <i class="fas fa-layer-group text-slate-400"></i> Tersedia: {{ $b->stok_tersedia }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl p-16 text-center border border-slate-200 shadow-sm mt-8">
                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Barang Sedang Kosong</h3>
                <p class="text-slate-500 text-sm">Maaf, saat ini belum ada barang umum yang bisa dipinjam.</p>
            </div>
        @endif

    </main>

    <div id="modalPinjam" class="hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center px-4 transition-opacity">
            <div class="bg-white rounded-4xl p-8 max-w-sm w-full shadow-2xl relative overflow-hidden transform scale-95 transition-transform duration-300" id="modalContent">
                
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fas fa-hand-holding-box"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 leading-tight" id="modal_nama_barang">Nama Barang</h3>
                    <p class="text-sm text-emerald-600 font-bold mt-1" id="modal_stok_info">Stok Tersedia: 0</p>
                </div>
                
                <form action="{{ route('user.peminjaman.umum.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barang_id" id="modal_barang_id">
                    
                    <div class="space-y-4 mb-8">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Jumlah Pinjam</label>
                            <input type="number" name="jumlah" id="modal_jumlah" min="1" value="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none text-center font-bold text-slate-700 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Alasan Peminjaman</label>
                            <textarea name="alasan" rows="2" required placeholder="Contoh: Mengganti mouse lab yang rusak..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none transition-all"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="tutupModal()" class="w-full px-4 py-3.5 rounded-xl font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="w-full px-4 py-3.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 transition-transform active:scale-95">
                            Pinjam
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalPinjam');
        const modalContent = document.getElementById('modalContent');

        function bukaModal(id, nama, stok) {
            // Isi data ke dalam modal
            document.getElementById('modal_barang_id').value = id;
            document.getElementById('modal_nama_barang').innerText = nama;
            document.getElementById('modal_jumlah').max = stok; 
            document.getElementById('modal_stok_info').innerText = "Stok Tersedia: " + stok;

            // Tampilan modal animasi
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function tutupModal() {
            // Tutup modal animasi
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
</body>
</html>