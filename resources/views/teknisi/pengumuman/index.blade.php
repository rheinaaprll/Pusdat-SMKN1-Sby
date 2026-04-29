@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Kelola Pengumuman</h2>
        <p class="text-slate-500 text-sm font-medium mt-1">Pusat informasi langsung ke Dashboard Peminjam.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-5 py-4 mb-6 rounded-xl shadow-sm font-medium flex items-center justify-between transition-all">
            <div class="flex items-center text-sm">
                <i class="fas fa-check-circle mr-3 text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if($pengumuman && $pengumuman->isi_pengumuman && $pengumuman->aktif_sampai > now())
        <div class="bg-indigo-600 rounded-2xl p-7 text-white shadow-md mb-8 relative overflow-hidden border border-indigo-500">
            <div class="absolute right-0 top-0 w-64 h-full bg-linear-to-l from-white/10 to-transparent pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5 border-b border-indigo-500/50 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                        </span>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-100">Sedang Tayang</span>
                    </div>
                    <div class="bg-indigo-700/50 px-3 py-1.5 rounded-lg border border-indigo-500/50 text-xs font-medium text-indigo-100">
                        <i class="far fa-clock mr-1"></i> Berakhir: {{ $pengumuman->aktif_sampai->translatedFormat('d M Y, H:i') }}
                    </div>
                </div>
                <p class="text-[15px] font-medium leading-relaxed">"{{ $pengumuman->isi_pengumuman }}"</p>
            </div>
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center mb-8 shadow-sm">
            <div class="w-14 h-14 bg-slate-50 border border-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-xl shadow-inner">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 class="text-slate-700 font-bold text-base">Belum Ada Pengumuman Aktif</h3>
            <p class="text-slate-500 text-sm mt-1">Dashboard pengguna saat ini bersih. Tulis pesan di bawah untuk memulai.</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                <i class="fas fa-pen text-sm"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Editor Pengumuman</h3>
        </div>
        
        <div class="p-8">
            <form action="{{ route('teknisi.pengumuman.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Isi Pesan</label>
                    <textarea name="isi_pengumuman" rows="4" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm resize-none" placeholder="Ketik pesan informasi di sini...">{{ $pengumuman ? $pengumuman->isi_pengumuman : '' }}</textarea>
                    <p class="text-xs text-slate-500 mt-2 flex items-start gap-1.5">
                        <i class="fas fa-info-circle text-slate-400 mt-0.5"></i> 
                        <span>Kosongkan teks lalu klik simpan untuk menarik pengumuman dari layar pengguna.</span>
                    </p>
                </div>

                <div class="mb-8 border-t border-slate-100 pt-6">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Durasi Penayangan</label>
                    <div class="relative w-full md:w-1/2">
                        <select name="durasi" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm appearance-none font-medium text-slate-700 cursor-pointer">
                            <option value="1_jam">Berlaku 1 Jam</option>
                            <option value="3_jam">Berlaku 3 Jam</option>
                            <option value="1_hari" selected>Berlaku 1 Hari (24 Jam)</option>
                            <option value="3_hari">Berlaku 3 Hari</option>
                            <option value="1_bulan">Berlaku 1 Bulan</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-[18px] text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm transition-all">
                        <i class="fas fa-paper-plane text-xs"></i> Publikasikan
                    </button>
                    
                    @if($pengumuman && $pengumuman->isi_pengumuman)
                        <button type="button" onclick="document.querySelector('textarea[name=isi_pengumuman]').value=''; this.form.submit();" class="bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                            <i class="fas fa-trash-alt text-xs"></i> Hapus Tayangan
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection