@extends('layouts.app') 

@section('content')
<div class="max-w-6xl mx-auto">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Menara Pemantau Koordinator</h1>
            <p class="text-sm text-slate-500 mt-1">Kontrol arus keluar-masuk barang gudang secara real-time.</p>
        </div>
        <div class="bg-white border border-slate-200 px-4 py-2 rounded-lg shadow-sm flex items-center gap-3">
            <i class="fas fa-server text-slate-400"></i>
            <span class="text-sm font-semibold text-slate-700">Status Gudang: Aktif</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-r-lg text-sm font-medium shadow-sm flex items-center gap-3 mb-6">
            <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if($peminjamans->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($peminjamans as $p)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden flex flex-col">
                    
                    <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">{{ $p->user->name }}</h3>
                            <p class="text-xs font-medium text-slate-500 mt-0.5"><i class="far fa-id-badge mr-1"></i> {{ $p->user->kelas_unit_kerja }} | NISN/NIP: {{ $p->user->nisn_nip }}</p>
                        </div>
                        
                        @if($p->status == 'bisa_diunduh')
                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border border-blue-200">
                                Siap Diambil
                            </span>
                        @else
                            <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border border-amber-200 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Sedang Dipinjam
                            </span>
                        @endif
                    </div>

                    <div class="px-6 py-5 flex-1">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Rincian Barang :</p>
                        <ul class="space-y-2.5">
                            @foreach($p->barangs as $barang)
                                <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-2 last:border-0 last:pb-0">
                                    <span class="font-medium text-slate-700 flex items-center gap-2">
                                        <i class="fas fa-cube text-slate-300 text-xs"></i> {{ $barang->nama_barang }}
                                    </span>
                                    <span class="bg-slate-100 text-slate-700 font-semibold px-2.5 py-1 rounded border border-slate-200 text-xs">{{ $barang->pivot->jumlah }} Unit</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                        @if($p->status == 'bisa_diunduh')
                            <form action="{{ route('koordinator.peminjaman.keluarkan', $p->id) }}" method="POST" onsubmit="return confirm('Keluarkan barang dari gudang? Stok akan berkurang otomatis.');">
                                @csrf
                                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium text-sm py-2.5 rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-sign-out-alt text-slate-300"></i> Keluarkan Barang
                                </button>
                            </form>
                        @elseif($p->status == 'sedang_dipinjam')
                            <form action="{{ route('koordinator.peminjaman.kembalikan', $p->id) }}" method="POST" onsubmit="return confirm('Proses pengembalian? Stok akan bertambah otomatis.');">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm py-2.5 rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-check-double text-emerald-100"></i> Terima Kembali Barang
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl p-12 text-center border border-slate-200 shadow-sm mt-6">
            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl border border-slate-100">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Gudang Aman Terkendali</h3>
            <p class="text-slate-500 text-sm">Tidak ada pergerakan barang yang membutuhkan tindakan Anda saat ini.</p>
        </div>
    @endif

</div>
@endsection