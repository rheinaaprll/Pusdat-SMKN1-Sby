@extends('layouts.app') 

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Rekapitulasi Sistem</h1>
            <p class="text-sm text-slate-500 mt-1">Arsip riwayat peminjaman dan unduh laporan sistem.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-5 py-4 rounded-r-xl shadow-sm font-medium flex items-center gap-3 mb-6">
            <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-5 py-4 rounded-r-xl shadow-sm font-medium flex items-center gap-3 mb-6">
            <i class="fas fa-exclamation-triangle text-lg"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-6 flex flex-col lg:flex-row justify-between items-center gap-4">
        
        <form action="{{ route('koordinator.rekapitulasi.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5">
                <i class="fas fa-filter text-slate-400 text-sm"></i>
                <select name="status" class="bg-transparent text-sm font-medium text-slate-700 outline-none cursor-pointer py-1">
                    <option value="semua">Semua Status</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai / Dikembalikan</option>
                    <option value="sedang_dipinjam" {{ request('status') == 'sedang_dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5">
                <i class="fas fa-tags text-slate-400 text-sm"></i>
                <select name="jenis_surat" class="bg-transparent text-sm font-medium text-slate-700 outline-none cursor-pointer py-1">
                    <option value="semua">Semua Kategori</option>
                    <option value="tanpa_surat" {{ request('jenis_surat') == 'tanpa_surat' ? 'selected' : '' }}>Barang Umum</option>
                    <option value="jurusan" {{ request('jenis_surat') == 'jurusan' ? 'selected' : '' }}>Surat - Jurusan</option>
                    <option value="guru_tendi" {{ request('jenis_surat') == 'guru_tendi' ? 'selected' : '' }}>Surat - Guru/Tendik</option>
                    <option value="ekstrakurikuler" {{ request('jenis_surat') == 'ekstrakurikuler' ? 'selected' : '' }}>Surat - Ekstrakurikuler</option>
                    <option value="ekskul_ke_jurusan" {{ request('jenis_surat') == 'ekskul_ke_jurusan' ? 'selected' : '' }}>Surat - Ekskul ke Jurusan</option>
                </select>
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                Terapkan
            </button>
            @if(request()->has('status') || request()->has('jenis_surat'))
                <a href="{{ route('koordinator.rekapitulasi.index') }}" class="text-sm text-slate-500 hover:text-rose-500 font-medium ml-2 transition-colors">Reset</a>
            @endif
        </form>

        <form action="{{ route('koordinator.rekapitulasi.laporan') }}" method="POST" target="_blank" class="flex items-center gap-2 w-full lg:w-auto border-t lg:border-t-0 lg:border-l border-slate-200 pt-4 lg:pt-0 lg:pl-4">
            @csrf
            <select name="rentang" class="bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-lg px-3 py-2 text-sm font-bold outline-none cursor-pointer">
                <option value="semua">Semua Waktu</option>
                <option value="harian">Laporan Harian</option>
                <option value="mingguan">Laporan Mingguan</option>
                <option value="bulanan">Laporan Bulanan</option>
            </select>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm shadow-indigo-200 flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Unduh PDF
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-widest">
                        <th class="py-4 px-6 font-bold">Waktu Transaksi</th>
                        <th class="py-4 px-6 font-bold">Peminjam</th>
                        <th class="py-4 px-6 font-bold">Tipe & Rincian</th>
                        <th class="py-4 px-6 font-bold">Status</th>
                        <th class="py-4 px-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    @forelse($riwayats as $r)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 align-top">
                                <p class="font-bold text-slate-800">{{ $r->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $r->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="py-4 px-6 align-top">
                                <p class="font-bold text-slate-800">{{ $r->user->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5"><i class="fas fa-id-badge text-slate-300 mr-1"></i>{{ $r->user->kelas_unit_kerja }}</p>
                            </td>
                            <td class="py-4 px-6 align-top max-w-xs">
                                @if($r->jenis_surat == 'tanpa_surat')
                                    <span class="inline-block bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase mb-1">Barang Umum</span>
                                @else
                                    <span class="inline-block bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase mb-1 border border-blue-100"><i class="fas fa-file-signature mr-1"></i> Surat Tercatat</span>
                                @endif
                                <p class="text-xs font-medium text-slate-600 truncate mt-1">
                                    {{ $r->barangs->count() }} Jenis Barang ({{ $r->barangs->pluck('nama_barang')->implode(', ') }})
                                </p>
                            </td>
                            <td class="py-4 px-6 align-top">
                                @if($r->status == 'selesai')
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase">Selesai</span>
                                @elseif($r->status == 'sedang_dipinjam')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase">Dipinjam</span>
                                @elseif($r->status == 'ditolak')
                                    <span class="bg-rose-50 text-rose-600 border border-rose-200 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase">Ditolak</span>
                                @else
                                    <span class="bg-indigo-50 text-indigo-600 border border-indigo-200 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase">Diproses</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 align-top text-center">
                                @if(auth()->user()->role == 'koordinator')
                                    <form action="{{ route('koordinator.rekapitulasi.destroy', $r->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus riwayat ini akan menghilangkannya dari database secara permanen. Lanjutkan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 p-2 rounded-lg transition-all" title="Hapus Riwayat">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">No Action</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <i class="fas fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                <p>Tidak ada riwayat transaksi yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $riwayats->links() }}
        </div>
    </div>

</div>
@endsection