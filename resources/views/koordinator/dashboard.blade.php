@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Pusat Kendali Koordinator</h2>
        <p class="text-slate-500 font-medium mt-1">Monitor seluruh aktivitas peminjaman secara Real-Time.</p>
    </div>
    <div class="text-left md:text-right">
        <p class="text-sm font-medium text-slate-500 bg-slate-100 px-4 py-2 rounded-xl inline-block shadow-sm border border-slate-200">
            <i class="far fa-clock mr-1 text-indigo-500"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full z-0 transition-transform group-hover:scale-110"></div>
        <div class="relative z-10 flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <span class="bg-rose-100 text-rose-700 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider">Live</span>
        </div>
        <div class="relative z-10">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Barang Keluar</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $barangKeluar }} <span class="text-sm font-medium text-slate-400">Unit</span></h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full z-0 transition-transform group-hover:scale-110"></div>
        <div class="relative z-10 flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-bell"></i>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Butuh Eksekusi</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $menungguAksi }} <span class="text-sm font-medium text-slate-400">Tugas</span></h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full z-0 transition-transform group-hover:scale-110"></div>
        <div class="relative z-10 flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-boxes-alt"></i>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total Inventaris</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $totalBarang }} <span class="text-sm font-medium text-slate-400">Item</span></h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-50 rounded-full z-0 transition-transform group-hover:scale-110"></div>
        <div class="relative z-10 flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total User</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $totalSiswa }} <span class="text-sm font-medium text-slate-400">Siswa</span></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 lg:col-span-2">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800 text-lg">Grafik Peminjaman Bulanan</h3>
            <a href="{{ route('koordinator.rekapitulasi.index') }}" class="text-sm text-indigo-600 font-bold hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors inline-block">
                <i class="fas fa-folder-open mr-1"></i> Unduh Laporan
            </a>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="peminjamanChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800 text-lg">Aktivitas Terbaru</h3>
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
        </div>
        
        <div class="space-y-4">
            @forelse($aktivitasTerbaru as $aktivitas)
                @php
                    $namaBarang = $aktivitas->barangs->first() ? $aktivitas->barangs->first()->nama_barang : 'Barang';
                    $waktu = $aktivitas->updated_at->diffForHumans();
                @endphp

                <div class="flex gap-4 items-start border-b border-slate-50 pb-4 last:border-0 last:pb-0">
                    
                    @if($aktivitas->status == 'selesai')
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $aktivitas->user->name }} mengembalikan {{ $namaBarang }}.</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $waktu }}</p>
                        </div>
                    @elseif($aktivitas->status == 'sedang_dipinjam')
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $aktivitas->user->name }} mengambil {{ $namaBarang }}.</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $waktu }}</p>
                        </div>
                    @elseif($aktivitas->status == 'ditolak')
                        <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-times"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Pengajuan {{ $aktivitas->user->name }} ditolak.</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $waktu }}</p>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $aktivitas->user->name }} mengajukan peminjaman.</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $waktu }}</p>
                        </div>
                    @endif
                </div>

            @empty
                <p class="text-sm text-slate-500 text-center italic py-4">Belum ada aktivitas terbaru.</p>
            @endforelse

            <div class="text-center pt-2">
                <a href="{{ route('koordinator.rekapitulasi.index') }}" class="text-sm text-indigo-600 font-bold hover:underline">Lihat Semua Aktivitas</a>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        
        // Mengambil data dari Controller
        const rawData = @json($dataGrafik);
        const labels = rawData.map(item => item.bulan); 
        const data = rawData.map(item => item.jumlah);
 
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');  
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');  

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Barang Dipinjam',
                    data: data,
                    borderColor: '#4f46e5',  
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,  
                    tension: 0.4  
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },  
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: "'Poppins', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Poppins', sans-serif" },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#64748b', font: { family: "'Poppins', sans-serif" } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { family: "'Poppins', sans-serif" } }
                    }
                }
            }
        });
    });
</script>
@endsection