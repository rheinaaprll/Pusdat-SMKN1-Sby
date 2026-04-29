 @extends('layouts.app')

@section('content')
<style>
    .btn-winter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        border-radius: 0.75rem; 
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
    }
    .btn-indigo { background-color: #4f46e5; color: white; }
    .btn-indigo:hover { background-color: #4338ca; transform: translateY(-2px); }
    
    .btn-emerald { background-color: #10b981; color: white; }
    .btn-emerald:hover { background-color: #059669; transform: translateY(-2px); }
    
    .btn-blue { background-color: #3b82f6; color: white; }
    .btn-blue:hover { background-color: #2563eb; transform: translateY(-2px); }
</style>

<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Halo, selamat datang {{ auth()->user()->name }}! 👋</h2>
        <p class="text-slate-500 font-medium mt-1">Berikut adalah ringkasan operasional teknisi hari ini.</p>
    </div>
    <div class="text-left md:text-right">
        <p class="text-sm font-medium text-slate-500 bg-slate-100 px-4 py-2 rounded-xl inline-block">
            <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
</div>

<div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden mb-10">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="relative z-10">
        <h4 class="text-xl font-bold mb-2 flex items-center gap-2">
            <i class="fas fa-lightbulb text-yellow-300"></i> Tips Teknisi Hari Ini
        </h4>
        <p class="text-indigo-100 max-w-2xl leading-relaxed font-medium">
            "{{ $tipHariIni }}"
        </p>
        <a href="{{ route('teknisi.users.index') }}" class="inline-block mt-6 bg-white text-indigo-700 px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-50 transition-colors">
            Cek Kelola Pengguna
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
    
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-105 hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Menunggu</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $pengajuanSurat }} <span class="text-sm font-medium text-slate-400">Berkas</span></h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-105 hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fas fa-hand-holding-box"></i>
        </div>
        <div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Dipinjam</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $barangDipinjam }} <span class="text-sm font-medium text-slate-400">Item</span></h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-105 hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fas fa-boxes-alt"></i>
        </div>
        <div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Inventaris</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $totalBarang }} <span class="text-sm font-medium text-slate-400">Alat</span></h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 transition-transform hover:scale-105 hover:shadow-md">
        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total Siswa</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $totalSiswa }} <span class="text-sm font-medium text-slate-400">Orang</span></h3>
        </div>
    </div>

</div>

<h2 class="text-xl font-bold text-slate-800 mb-4">Akses Cepat Sistem</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-lg"></i>
                </div>
                <h3 class="font-bold text-slate-700">Verifikasi Surat</h3>
            </div>
            <p class="text-sm text-slate-500 mb-6">Cek pengajuan surat dari user dan verifikasi ketersediaan barang.</p>
        </div>
        <a href="{{ route('teknisi.peminjaman.index') }}" class="btn-winter btn-indigo w-full gap-2">
            Buka Peminjaman <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <i class="fas fa-bullhorn text-lg"></i>
                </div>
                <h3 class="font-bold text-slate-700">Pengumuman</h3>
            </div>
            <p class="text-sm text-slate-500 mb-6">Posting info penting untuk User (Siswa/Guru). Tampil di dashboard mereka.</p>
        </div>
        <a href="{{ route('teknisi.pengumuman.index') }}" class="btn-winter btn-blue w-full gap-2">
            Kelola Pengumuman <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i class="fas fa-file-pdf text-lg"></i>
                </div>
                <h3 class="font-bold text-slate-700">Laporan Peminjaman</h3>
            </div>
            <p class="text-sm text-slate-500 mb-6">Cetak dokumen PDF dan rekapitulasi data riwayat peminjaman.</p>
        </div>
        <a href="{{ route('koordinator.rekapitulasi.index') }}" class="btn-winter btn-emerald w-full gap-2">
            Buka Laporan <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</div>
@endsection