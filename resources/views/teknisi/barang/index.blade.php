@extends('layouts.app')

@section('content')
<style>
    .btn-biru-solid { background-color: #2563eb; color: white; }
    .btn-biru-solid:hover { background-color: #1d4ed8; }
    
    .btn-putih-outline { background-color: white; border: 1px solid #e2e8f0; color: #475569; }
    .btn-putih-outline:hover { background-color: #f8fafc; color: #0f172a; }

    /* Animasi Modal Blur */
    .modal-muncul { animation: fadeIn 0.2s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<div class="mb-6 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Data Inventaris</h2>
        <p class="text-slate-500 text-sm mt-1 font-medium">Kelola daftar alat dan barang Teknisi SMKN 1 Surabaya.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
        
        <form action="{{ route('teknisi.barang.index') }}" method="GET" class="flex items-center">
            @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
            
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..." class="pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 shadow-sm w-48 transition-all">
            </div>
        </form>

        <div class="bg-white border border-slate-200 rounded-lg p-1 flex shadow-sm text-sm">
            <a href="{{ route('teknisi.barang.index', ['filter' => 'semua', 'search' => request('search')]) }}" class="px-4 py-1.5 rounded-md font-medium transition-colors {{ request('filter') == 'semua' || !request('filter') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50' }}">Semua</a>
            <a href="{{ route('teknisi.barang.index', ['filter' => 'tersedia', 'search' => request('search')]) }}" class="px-4 py-1.5 rounded-md font-medium transition-colors {{ request('filter') == 'tersedia' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50' }}">Tersedia</a>
            <a href="{{ route('teknisi.barang.index', ['filter' => 'dipinjam', 'search' => request('search')]) }}" class="px-4 py-1.5 rounded-md font-medium transition-colors {{ request('filter') == 'dipinjam' ? 'bg-amber-50 text-amber-700' : 'text-slate-500 hover:bg-slate-50' }}">Dipinjam</a>
        </div>

         @if(auth()->user()->role == 'teknisi')
        <div class="relative" id="dropdownContainer">
            <button onclick="toggleDropdown()" class="btn-biru-solid px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                Kelola Inventaris <i class="fas fa-chevron-down text-xs ml-1"></i>
            </button>
            
            <div id="inventarisMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-20">
                <a href="{{ route('teknisi.barang.create') }}" class="block px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 border-b border-slate-50 transition-colors">
                    <i class="fas fa-plus-circle w-5"></i> Tambah Barang
                </a>
                <button onclick="bukaModalKategori()" class="w-full text-left px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                    <i class="fas fa-tags w-5"></i> Kategori Barang
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-lg shadow-sm flex justify-between items-center font-medium">
        <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" class="text-emerald-700 hover:text-emerald-900"><i class="fas fa-times"></i></button>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative z-10">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <th class="p-5 font-bold">Kode Barang</th>
                    <th class="p-5 font-bold">Nama Alat / Barang</th>
                    <th class="p-5 font-bold">Kategori</th>
                    <th class="p-5 font-bold text-center">Stok Total</th>
                    <th class="p-5 font-bold text-center">Tersedia</th>
                    <th class="p-5 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($barangs as $brg)
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="p-5 font-bold text-blue-600">{{ $brg->kode_barang }}</td>
                    <td class="p-5 font-semibold text-slate-800">{{ $brg->nama_barang }} <br><span class="text-xs text-slate-400 font-normal">{{ $brg->merk ?? '-' }}</span></td>
                    <td class="p-5 text-slate-600"><span class="bg-slate-100 text-slate-600 py-1 px-3 rounded-lg text-xs font-bold">{{ $brg->kategori }}</span></td>
                    <td class="p-5 text-center font-bold text-slate-700">{{ $brg->stok_total }}</td>
                    <td class="p-5 text-center">
                        @if($brg->stok_tersedia == 0)
                            <span class="bg-rose-100 text-rose-700 py-1 px-3 rounded-lg text-xs font-bold">Kosong</span>
                        @elseif($brg->stok_tersedia < $brg->stok_total)
                            <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-lg text-xs font-bold">{{ $brg->stok_tersedia }} Dipinjam</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-lg text-xs font-bold">{{ $brg->stok_tersedia }} Tersedia</span>
                        @endif
                    </td>
                     <td class="p-5 text-center relative">
                        @if(auth()->user()->role == 'teknisi')
                            <button onclick="toggleActionMenu({{ $brg->id }})" class="text-slate-400 hover:text-slate-700 focus:outline-none w-8 h-8 rounded-full hover:bg-slate-100 transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>

                            <div id="actionMenu-{{ $brg->id }}" class="hidden absolute right-12 top-2 w-32 bg-white rounded-lg shadow-lg border border-slate-100 overflow-hidden z-20">
                                <a href="{{ route('teknisi.barang.edit', $brg->id) }}" class="block px-4 py-2 text-xs font-semibold text-left text-slate-700 hover:bg-blue-50 hover:text-blue-700">
                                    <i class="fas fa-edit mr-2"></i> Edit Data
                                </a>
                                <form action="{{ route('teknisi.barang.destroy', $brg->id) }}" method="POST" class="block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                                        <i class="fas fa-trash mr-2"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs text-slate-400 italic">Hanya Pantau</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-slate-400 font-medium">Belum ada data barang. Silakan tambah barang baru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-5 border-t border-slate-100">
        {{ $barangs->links() }}
    </div>
</div>

<div id="kategoriModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-muncul">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModalKategori()"></div>
    
  <div class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4 z-10">
        <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-tags text-indigo-500 mr-2"></i> Tambah Kategori</h3>
            <button onclick="tutupModalKategori()" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('teknisi.kategori.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-bold mb-2">Nama Kategori Baru</label>
                <input type="text" name="nama_kategori" required placeholder="Contoh: Alat Olahraga" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all text-sm">
                <p class="text-[10px] text-slate-400 mt-2 italic">*Sistem akan otomatis mengambil 3 huruf pertama sebagai kode unik.</p>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="tutupModalKategori()" class="btn-putih-outline px-4 py-2 rounded-lg text-sm font-bold">Batal</button>
                <button type="submit" class="btn-biru-solid px-4 py-2 rounded-lg text-sm font-bold">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. Script Dropdown Kelola Inventaris
    function toggleDropdown() {
        document.getElementById('inventarisMenu').classList.toggle('hidden');
    }

    // 2. Script Titik Tiga (Action Menu)
    function toggleActionMenu(id) {
        document.querySelectorAll('[id^="actionMenu-"]').forEach(el => {
            if(el.id !== 'actionMenu-'+id) el.classList.add('hidden');
        });
        document.getElementById('actionMenu-' + id).classList.toggle('hidden');
    }

    // 3. Script Modal Kategori
    function bukaModalKategori() {
        document.getElementById('kategoriModal').classList.remove('hidden');
        document.getElementById('kategoriModal').classList.add('flex');
        document.getElementById('inventarisMenu').classList.add('hidden'); // Tutup dropdown utama
    }

    function tutupModalKategori() {
        document.getElementById('kategoriModal').classList.add('hidden');
        document.getElementById('kategoriModal').classList.remove('flex');
    }

    // 4. Tutup dropdown jika klik di Luar kotak
    document.addEventListener('click', function(event) {
        const dropContainer = document.getElementById('dropdownContainer');
        if (!dropContainer.contains(event.target)) {
            document.getElementById('inventarisMenu').classList.add('hidden');
        }
        
        // Tutup titik tiga jika klik di luar
        if (!event.target.closest('td')) {
            document.querySelectorAll('[id^="actionMenu-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
</script>
@endsection