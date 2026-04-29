@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Kelola SOP Sistem</h2>
        <p class="text-slate-500 text-sm font-medium mt-1">Perbarui teks Standar Operasional Prosedur untuk pengguna.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-5 py-4 mb-6 rounded-xl shadow-sm font-medium flex items-center justify-between transition-all">
            <div class="flex items-center text-sm">
                <i class="fas fa-check-circle mr-3 text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3 bg-indigo-50/30">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center border border-indigo-200">
                    <i class="fas fa-clipboard-list text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">SOP Peminjaman</h3>
                    <p class="text-xs text-slate-500 font-medium">Syarat & tata cara meminjam barang</p>
                </div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <form action="{{ route('teknisi.sop.store') }}" method="POST" class="flex-1 flex flex-col">
                    @csrf
                    <input type="hidden" name="jenis" value="peminjaman">
                    
                    <div class="mb-4 flex-1">
                        <textarea name="isi_sop" rows="12" class="w-full h-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm resize-none" placeholder="1. Peminjam wajib menyerahkan Kartu Pelajar...&#10;2. Peminjam wajib mengecek kondisi barang...&#10;3. Dst...">{{ $sopPeminjaman ? $sopPeminjaman->isi_sop : '' }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-auto">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition-all">
                             <h3 class="text-base font-bold text-slate-800"></i>Simpan SOP Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3 bg-emerald-50/30">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center border border-emerald-200">
                    <i class="fas fa-box-open text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">SOP Pengembalian</h3>
                    <p class="text-xs text-slate-500 font-medium">Syarat & tata cara mengembalikan barang</p>
                </div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <form action="{{ route('teknisi.sop.store') }}" method="POST" class="flex-1 flex flex-col">
                    @csrf
                    <input type="hidden" name="jenis" value="pengembalian">
                    
                    <div class="mb-4 flex-1">
                        <textarea name="isi_sop" rows="12" class="w-full h-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm resize-none" placeholder="1. Barang dikembalikan tepat waktu...&#10;2. Barang dibersihkan sebelum dikembalikan...&#10;3. Dst...">{{ $sopPengembalian ? $sopPengembalian->isi_sop : '' }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-auto">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition-all">
                            <h3 class="text-base font-bold text-slate-800"></i>Simpan SOP Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection