@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Antrean Pengajuan Surat</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Daftar peminjaman Barang Tercatat yang membutuhkan verifikasi Anda.</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-bold text-sm border border-blue-100 flex items-center gap-2 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            {{ $antreanSurat->count() }} Surat Menunggu
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-5 py-4 mb-6 rounded-xl shadow-sm font-medium flex items-center justify-between transition-all">
            <div class="flex items-center text-sm">
                <i class="fas fa-check-circle mr-3 text-emerald-500 text-lg"></i> {{ session('success') }}
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @if($antreanSurat->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4">Tanggal Pengajuan</th>
                            <th scope="col" class="px-6 py-4">Peminjam</th>
                            <th scope="col" class="px-6 py-4">Jenis Surat</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($antreanSurat as $surat)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($surat->created_at)->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $surat->user->name }}</div>
                                <div class="text-xs text-slate-500 font-medium">
                                    <span class="text-slate-400">NISN/NIP:</span> {{ $surat->user->nisn_nip }} • 
                                    <span class="text-slate-400">Kls/Unit:</span> {{ $surat->user->kelas_unit_kerja }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($surat->jenis_surat == 'jurusan')
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">Atas Nama Jurusan</span>
                                @elseif($surat->jenis_surat == 'ekstrakurikuler')
                                    <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-100">Ekstrakurikuler</span>
                                @elseif($surat->jenis_surat == 'ekskul_ke_jurusan')
                                    <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-bold border border-amber-100">Ekskul ke Jurusan</span>
                                @elseif($surat->jenis_surat == 'guru_tendi')
                                    <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-xs font-bold border border-purple-100">Guru / Tendik</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-amber-600 font-bold text-xs bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                                    <i class="fas fa-clock"></i> Perlu Verifikasi
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('teknisi.peminjaman.show', $surat->id) }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                    <i class="fas fa-search"></i> Cek & Proses
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak Ada Antrean</h3>
                <p class="text-slate-500 text-sm">Semua pengajuan surat telah diverifikasi. Pekerjaan Anda selesai!</p>
            </div>
        @endif
    </div>
</div>
@endsection