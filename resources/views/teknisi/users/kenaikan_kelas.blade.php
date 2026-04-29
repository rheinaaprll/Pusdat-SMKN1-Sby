@extends('layouts.app')

@section('content')
<style>
    .btn-eksekusi {
        background: linear-gradient(to right, #2563eb, #4f46e5);
        color: white;
    }
    .btn-eksekusi:hover {
        background: linear-gradient(to right, #1d4ed8, #4338ca);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
    }
</style>

<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kenaikan Kelas Massal</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium">Pindahkan data siswa ke kelas baru sekaligus.</p>
        </div>
        <a href="{{ route('teknisi.users.index') }}" class="text-slate-500 hover:text-blue-600 font-bold transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded-lg shadow-sm font-medium flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-rose-500"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl border border-slate-200 p-8 rounded-3xl shadow-xl relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-bl-[100px] -z-10 opacity-50"></div>

        <form action="{{ route('teknisi.users.proses-kenaikan') }}" method="POST">
            @csrf

            <div class="bg-blue-50 text-blue-800 p-4 rounded-xl mb-6 text-sm font-medium border border-blue-100 flex gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <p>Pilih kelas yang siswanya ingin dipindahkan, lalu ketik nama kelas tujuannya. Jika siswa kelas 12 sudah lulus, ketikkan "Alumni" pada kolom kelas baru.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                
                <div>
                    <label class="block text-slate-700 text-xs font-bold mb-2 uppercase tracking-wider">Dari Kelas Lama</label>
                    <div class="relative">
                        <select name="kelas_lama" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all appearance-none cursor-pointer font-semibold">
                            <option value="">-- Pilih Kelas Saat Ini --</option>
                            @forelse($daftarKelas as $kelas)
                                @if($kelas != '-' && $kelas != '')
                                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                                @endif
                            @empty
                                <option value="">Belum ada data kelas</option>
                            @endforelse
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="hidden md:flex justify-center pb-3 text-blue-400">
                    <i class="fas fa-arrow-right text-xl"></i>
                </div>

                <div class="md:col-span-2 mt-2">
                    <label class="block text-slate-700 text-xs font-bold mb-2 uppercase tracking-wider">Pindahkan Menjadi Kelas</label>
                    <div class="relative">
                        <i class="fas fa-graduation-cap absolute left-4 top-3.5 text-blue-400"></i>
                        <input type="text" name="kelas_baru" required placeholder="Contoh: 11 RPL 1, Alumni" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold">
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memindahkan seluruh siswa di kelas tersebut?')" class="btn-eksekusi font-bold py-3 px-8 rounded-xl shadow-lg transform transition-all hover:-translate-y-0.5 focus:outline-none flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Naikkan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection