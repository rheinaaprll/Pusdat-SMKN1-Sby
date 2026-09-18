@extends('layouts.app')

@section('content')

<style>
    @media print {
        aside, header, #header-halaman, #tabel-utama, .bg-blue-50 { display: none !important; }
        body { background-color: white !important; }
        #area-cetak {
            display: block !important; position: absolute !important; left: 0 !important; top: 0 !important;
            width: 100% !important; margin: 0 !important; padding: 0 !important; border: none !important; box-shadow: none !important;
        }
        .jangan-cetak { display: none !important; }
        .hilangkan-scroll-saat-print { max-height: none !important; overflow: visible !important; border: none !important; }
        #area-cetak table { width: 100% !important; border-collapse: collapse !important; margin-top: 15px !important; }
        #area-cetak th, #area-cetak td {
            border: 1px solid black !important; padding: 8px 12px !important; font-size: 12pt !important;
            color: black !important; font-family: 'Times New Roman', Times, serif !important; background-color: transparent !important;
        }
        #area-cetak th {
            background-color: #b4c6e7 !important; -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; font-weight: bold !important; text-align: center !important;
        }
        #area-cetak td:nth-child(1) { text-align: center !important; width: 5%; }
        #area-cetak td:nth-child(3) { text-align: center !important; width: 25%; }
        #area-cetak td:nth-child(4) { text-align: center !important; color: #0000ee !important; font-weight: bold !important; width: 30%; }
        #area-cetak .judul-dokumen {
            text-align: center !important; border: none !important; color: black !important;
            font-family: 'Times New Roman', Times, serif !important; font-size: 16pt !important; font-weight: bold !important; margin-bottom: 5px !important; text-transform: uppercase !important;
        }
        #area-cetak .tanggal-cetak {
            display: block !important; text-align: center !important; color: black !important;
            font-family: 'Times New Roman', Times, serif !important; font-size: 11pt !important; margin-bottom: 20px !important;
        }
    }
</style>

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Kelola Pengguna</h1>
        <p class="text-slate-500 text-sm font-medium mt-1">Manajemen data Siswa, Guru, dan Tenaga Pendidik.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3 relative">
        
        <a href="{{ route('teknisi.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm shadow-indigo-200 flex items-center gap-2 transition-all">
            <i class="fas fa-user-plus"></i> Tambah Manual
        </a>

        <button id="btnOpsiUser" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm flex items-center gap-2 transition-all focus:outline-none">
            <i class="fas fa-sliders-h text-indigo-500"></i> Opsi Lanjutan 
            <i class="fas fa-chevron-down text-xs ml-1 text-slate-400 transition-transform duration-300" id="iconOpsiUser"></i>
        </button>
        
        <div id="dropdownOpsiUser" class="hidden absolute right-0 top-full mt-3 w-64 bg-white border border-slate-100 rounded-2xl shadow-xl transform opacity-0 scale-95 transition-all duration-200 z-50 overflow-hidden">
            
            <button onclick="bukaModal('modalExcel')" class="w-full text-left px-4 py-3 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 font-medium border-b border-slate-50 flex items-center gap-3 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i class="fas fa-file-excel"></i></div>
                Import Data Excel
            </button>
            
            @if(auth()->user()->role == 'koordinator' || auth()->user()->role == 'koordinator_teknisi')
            <button onclick="bukaModal('modalRfid')" class="w-full text-left px-4 py-3 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 font-medium border-b border-slate-50 flex items-center gap-3 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0"><i class="fas fa-id-badge"></i></div>
                Import Data RFID
            </button>
            @endif
            
            <a href="{{ url('teknisi/users/kenaikan-kelas') }}" class="w-full text-left px-4 py-3 text-sm text-blue-600 hover:bg-blue-50 font-bold flex items-center gap-3 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0"><i class="fas fa-level-up-alt"></i></div>
                Naikkan Kelas Siswa
            </a>
        </div>
    </div>
</div>

<form action="{{ route('teknisi.users.index') }}" method="GET" class="mb-6 flex gap-3">
    <div class="relative flex-1 max-w-md">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
    </div>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all">
        Cari Data
    </button>
    @if(request('search'))
        <a href="{{ route('teknisi.users.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-3 rounded-xl text-sm font-bold shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-undo"></i> Reset
        </a>
    @endif
</form>

@if(session('success'))
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-sm flex justify-between items-center font-medium">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" class="text-blue-700 hover:text-blue-900"><i class="fas fa-times"></i></button>
    </div>
@endif

@if(session('error'))
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded-lg shadow-sm flex justify-between items-center font-medium">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.style.display='none'" class="text-rose-700 hover:text-rose-900"><i class="fas fa-times"></i></button>
    </div>
@endif

@if(session('akunBaru'))
    @php session()->keep(['akunBaru']); @endphp
    <div id="area-cetak" class="bg-white border-2 border-indigo-200 p-6 mb-6 rounded-2xl shadow-lg relative">
        <div class="mb-4">
            <h3 class="text-xl font-bold text-slate-800 border-b-2 border-slate-200 pb-2 judul-dokumen">
                <i class="fas fa-users text-indigo-500 mr-2 jangan-cetak"></i> Daftar Akun & Password Baru
            </h3>
            <p class="text-sm text-slate-500 mt-2 jangan-cetak">Mohon simpan atau cetak password di bawah ini sekarang. Password ini tidak akan ditampilkan lagi setelah Anda memuat ulang halaman.</p>
            <p class="tanggal-cetak hidden print:block">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} - SMKN 1 Surabaya</p>
        </div>
        <div class="max-h-64 overflow-y-auto border border-slate-200 rounded-lg hilangkan-scroll-saat-print">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-indigo-50 text-indigo-800">
                        <th class="p-3 border-b border-slate-200 font-bold">NO</th>
                        <th class="p-3 border-b border-slate-200 font-bold">Nama</th>
                        <th class="p-3 border-b border-slate-200 font-bold">NISN / NIP</th>
                        <th class="p-3 border-b border-slate-200 font-bold">Password Default</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('akunBaru') as $index => $akun)
                        <tr class="hover:bg-slate-50 border-b border-slate-100">
                            <td class="p-3 text-slate-600 text-center">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold text-slate-700">{{ $akun['nama'] }}</td>
                            <td class="p-3 text-slate-600">{{ $akun['nisn_nip'] }}</td>
                            <td class="p-3 font-mono font-bold text-indigo-600 bg-indigo-50/50">{{ $akun['password'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('teknisi.users.pdf-password') }}" class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition-all inline-flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Unduh Daftar Password (PDF)
        </a>
    </div>
@endif

<div id="tabel-utama" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    
    <div class="flex border-b border-slate-200 bg-slate-50">
        <button onclick="switchTab('siswa')" id="tab-siswa" class="flex-1 py-4 text-sm font-bold text-blue-700 border-b-2 border-blue-700 hover:bg-slate-100 transition-colors uppercase tracking-wider">Data Siswa</button>
        <button onclick="switchTab('guru')" id="tab-guru" class="flex-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-blue-600 hover:bg-slate-100 transition-colors uppercase tracking-wider">Data Guru</button>
        <button onclick="switchTab('tendik')" id="tab-tendik" class="flex-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-blue-600 hover:bg-slate-100 transition-colors uppercase tracking-wider">Tenaga Pendidik</button>
        <button onclick="switchTab('pengelola')" id="tab-pengelola" class="flex-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-blue-600 hover:bg-slate-100 transition-colors uppercase tracking-wider">Pengelola Sistem</button>
    </div>

    <div id="content-siswa" class="p-0 block">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <th class="p-5 font-bold">Nama Lengkap</th>
                    <th class="p-5 font-bold">NISN</th>
                    <th class="p-5 font-bold">No. HP</th>
                    <th class="p-5 font-bold">Kelas</th>
                    <th class="p-5 font-bold text-center">Status RFID</th>
                    <th class="p-5 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($siswa as $s)
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="p-5 font-semibold text-slate-800">{{ $s->name }}</td>
                    <td class="p-5 text-slate-600 font-medium">{{ $s->nisn_nip }}</td>
                    <td class="p-5 text-slate-600">{{ $s->no_hp ?? '-' }}</td>
                    <td class="p-5 text-slate-600"><span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-lg text-xs font-bold">{{ $s->kelas_unit_kerja }}</span></td>
                    <td class="p-5 text-center">
                        @if($s->rfid)
                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="bg-rose-100 text-rose-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-times-circle mr-1"></i> Kosong</span>
                        @endif
                    </td>
                    <td class="p-5 text-center whitespace-nowrap relative">
                        <button onclick="toggleAksi('aksi-siswa-{{ $s->id }}')" class="text-slate-400 hover:text-indigo-600 px-3 py-1 transition-colors outline-none">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </button>
                        
                        <div id="aksi-siswa-{{ $s->id }}" class="hidden absolute right-12 top-1/2 -translate-y-1/2 w-44 bg-white border border-slate-100 rounded-xl shadow-xl z-50 overflow-hidden text-left">
                            <form action="{{ route('teknisi.users.reset-password', $s->id) }}" method="POST" onsubmit="return confirm('Reset password untuk {{ $s->name }}?');">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2 transition-colors"><i class="fas fa-key w-4 text-center"></i> Reset Password</button>
                            </form>
                            <a href="{{ route('teknisi.users.edit', $s->id) }}" class="w-full text-left px-4 py-2.5 text-xs font-bold text-indigo-600 hover:bg-indigo-50 flex items-center gap-2 border-t border-slate-50 transition-colors"><i class="fas fa-edit w-4 text-center"></i> Edit Data</a>
                            <form action="{{ route('teknisi.users.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa {{ $s->name }} secara permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 border-t border-slate-50 transition-colors"><i class="fas fa-trash w-4 text-center"></i> Hapus Data</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-slate-400 font-medium">Belum ada data Siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $siswa->links() }}</div>
    </div>

    <div id="content-guru" class="p-0 hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <th class="p-5 font-bold">Nama Lengkap</th>
                    <th class="p-5 font-bold">NIP</th>
                    <th class="p-5 font-bold">No. HP</th>
                    <th class="p-5 font-bold">Unit Kerja</th>
                    <th class="p-5 font-bold text-center">Status RFID</th>
                    <th class="p-5 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($guru as $g)
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="p-5 font-semibold text-slate-800">{{ $g->name }}</td>
                    <td class="p-5 text-slate-600 font-medium">{{ is_numeric($g->nisn_nip) ? $g->nisn_nip : '-' }}</td>
                    <td class="p-5 text-slate-600">{{ $g->no_hp ?? '-' }}</td>
                    <td class="p-5 text-slate-600"><span class="bg-slate-100 text-slate-700 py-1 px-3 rounded-lg text-xs font-bold">{{ $g->kelas_unit_kerja }}</span></td>
                    <td class="p-5 text-center">
                        @if($g->rfid)
                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="bg-rose-100 text-rose-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-times-circle mr-1"></i> Kosong</span>
                        @endif
                    </td>
                    <td class="p-5 text-center whitespace-nowrap relative">
                        <button onclick="toggleAksi('aksi-guru-{{ $g->id }}')" class="text-slate-400 hover:text-indigo-600 px-3 py-1 transition-colors outline-none">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </button>
                        <div id="aksi-guru-{{ $g->id }}" class="hidden absolute right-12 top-1/2 -translate-y-1/2 w-44 bg-white border border-slate-100 rounded-xl shadow-xl z-50 overflow-hidden text-left">
                            <form action="{{ route('teknisi.users.reset-password', $g->id) }}" method="POST" onsubmit="return confirm('Reset password untuk {{ $g->name }}?');">
                                @csrf <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2"><i class="fas fa-key w-4 text-center"></i> Reset Password</button>
                            </form>
                            <a href="{{ route('teknisi.users.edit', $g->id) }}" class="w-full px-4 py-2.5 text-xs font-bold text-indigo-600 hover:bg-indigo-50 flex items-center gap-2 border-t border-slate-50"><i class="fas fa-edit w-4 text-center"></i> Edit Data</a>
                            <form action="{{ route('teknisi.users.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus data guru {{ $g->name }} secara permanen?');">
                                @csrf @method('DELETE') <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 border-t border-slate-50"><i class="fas fa-trash w-4 text-center"></i> Hapus Data</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-slate-400 font-medium">Belum ada data Guru.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $guru->links() }}</div>
    </div>

    <div id="content-tendik" class="p-0 hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <th class="p-5 font-bold">Nama Lengkap</th>
                    <th class="p-5 font-bold">NIP</th>
                    <th class="p-5 font-bold">No. HP</th>
                    <th class="p-5 font-bold">Unit Kerja</th>
                    <th class="p-5 font-bold text-center">Status RFID</th>
                    <th class="p-5 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($tendik as $t)
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="p-5 font-semibold text-slate-800">{{ $t->name }}</td>
                    <td class="p-5 text-slate-600 font-medium">{{ is_numeric($t->nisn_nip) ? $t->nisn_nip : '-' }}</td>
                    <td class="p-5 text-slate-600">{{ $t->no_hp ?? '-' }}</td>
                    <td class="p-5 text-slate-600"><span class="bg-slate-100 text-slate-700 py-1 px-3 rounded-lg text-xs font-bold">{{ $t->kelas_unit_kerja }}</span></td>
                    <td class="p-5 text-center">
                        @if($t->rfid)
                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="bg-rose-100 text-rose-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-times-circle mr-1"></i> Kosong</span>
                        @endif
                    </td>
                    <td class="p-5 text-center whitespace-nowrap relative">
                        <button onclick="toggleAksi('aksi-tendik-{{ $t->id }}')" class="text-slate-400 hover:text-indigo-600 px-3 py-1 transition-colors outline-none">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </button>
                        <div id="aksi-tendik-{{ $t->id }}" class="hidden absolute right-12 top-1/2 -translate-y-1/2 w-44 bg-white border border-slate-100 rounded-xl shadow-xl z-50 overflow-hidden text-left">
                            <form action="{{ route('teknisi.users.reset-password', $t->id) }}" method="POST" onsubmit="return confirm('Reset password untuk {{ $t->name }}?');">
                                @csrf <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2"><i class="fas fa-key w-4 text-center"></i> Reset Password</button>
                            </form>
                            <a href="{{ route('teknisi.users.edit', $t->id) }}" class="w-full px-4 py-2.5 text-xs font-bold text-indigo-600 hover:bg-indigo-50 flex items-center gap-2 border-t border-slate-50"><i class="fas fa-edit w-4 text-center"></i> Edit Data</a>
                            <form action="{{ route('teknisi.users.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus data tendik {{ $t->name }} secara permanen?');">
                                @csrf @method('DELETE') <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 border-t border-slate-50"><i class="fas fa-trash w-4 text-center"></i> Hapus Data</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-slate-400 font-medium">Belum ada data Tenaga Pendidik.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $tendik->links() }}</div>
    </div>

    <div id="content-pengelola" class="p-0 hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <th class="p-5 font-bold">Nama Lengkap</th>
                    <th class="p-5 font-bold">NIP</th>
                    <th class="p-5 font-bold">No. HP</th>
                    <th class="p-5 font-bold">Jabatan</th>
                    <th class="p-5 font-bold text-center">Status RFID</th>
                    <th class="p-5 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($pengelola as $p)
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="p-5 font-semibold text-slate-800">{{ $p->name }}</td>
                    <td class="p-5 text-slate-600 font-medium">{{ is_numeric($p->nisn_nip) ? $p->nisn_nip : '-' }}</td>
                    <td class="p-5 text-slate-600">{{ $p->no_hp ?? '-' }}</td>
                    <td class="p-5 text-slate-600">
                        <span class="bg-indigo-100 text-indigo-700 py-1 px-3 rounded-lg text-xs font-bold uppercase">{{ $p->role }}</span>
                    </td>
                    <td class="p-5 text-center">
                        @if($p->rfid)
                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="bg-rose-100 text-rose-700 py-1 px-3 rounded-lg text-xs font-bold"><i class="fas fa-times-circle mr-1"></i> Kosong</span>
                        @endif
                    </td>
                    <td class="p-5 text-center whitespace-nowrap relative">
                        @if(auth()->user()->id != $p->id)
                            <button onclick="toggleAksi('aksi-pengelola-{{ $p->id }}')" class="text-slate-400 hover:text-indigo-600 px-3 py-1 transition-colors outline-none">
                                <i class="fas fa-ellipsis-v text-lg"></i>
                            </button>
                            <div id="aksi-pengelola-{{ $p->id }}" class="hidden absolute right-12 top-1/2 -translate-y-1/2 w-44 bg-white border border-slate-100 rounded-xl shadow-xl z-50 overflow-hidden text-left">
                                <form action="{{ route('teknisi.users.reset-password', $p->id) }}" method="POST" onsubmit="return confirm('Reset password untuk {{ $p->name }}?');">
                                    @csrf <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2"><i class="fas fa-key w-4 text-center"></i> Reset Password</button>
                                </form>
                                <a href="{{ route('teknisi.users.edit', $p->id) }}" class="w-full px-4 py-2.5 text-xs font-bold text-indigo-600 hover:bg-indigo-50 flex items-center gap-2 border-t border-slate-50"><i class="fas fa-edit w-4 text-center"></i> Edit Data</a>
                                <form action="{{ route('teknisi.users.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data {{ $p->name }} secara permanen?');">
                                    @csrf @method('DELETE') <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 border-t border-slate-50"><i class="fas fa-trash w-4 text-center"></i> Hapus Data</button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs font-bold text-emerald-500 bg-emerald-100 py-1 px-3 rounded-lg"><i class="fas fa-user-check"></i> Anda (Login)</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-slate-400 font-medium">Belum ada data Pengelola.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalExcel" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center px-4 transition-opacity">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl relative">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-file-excel text-emerald-500 mr-2"></i> Import Data Excel</h3>
            <button onclick="tutupModal('modalExcel')" class="text-slate-400 hover:text-rose-500"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form action="{{ url('teknisi/users/import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File Excel (.xlsx, .xls)</label>
                <input type="file" name="file_excel" required accept=".xlsx, .xls, .csv" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="tutupModal('modalExcel')" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">Mulai Import</button>
            </div>
        </form>
    </div>
</div>

<div id="modalRfid" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center px-4 transition-opacity">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl relative">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-id-badge text-amber-500 mr-2"></i> Import Data RFID</h3>
            <button onclick="tutupModal('modalRfid')" class="text-slate-400 hover:text-rose-500"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form action="{{ url('teknisi/users/import-rfid') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File Excel RFID (.xlsx, .xls)</label>
                <input type="file" name="file_rfid" required accept=".xlsx, .xls, .csv" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="tutupModal('modalRfid')" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 transition-colors">Update RFID</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab Tabel
    function switchTab(tabName) {
        document.getElementById('content-siswa').classList.add('hidden');
        document.getElementById('content-guru').classList.add('hidden');
        document.getElementById('content-tendik').classList.add('hidden');
        document.getElementById('content-pengelola').classList.add('hidden');  
        
        let tabs = ['siswa', 'guru', 'tendik', 'pengelola'];  
        tabs.forEach(t => {
            let btn = document.getElementById('tab-' + t);
            btn.classList.remove('text-blue-700', 'border-blue-700');
            btn.classList.add('text-slate-400', 'border-transparent');
        });

        document.getElementById('content-' + tabName).classList.remove('hidden');
        let activeBtn = document.getElementById('tab-' + tabName);
        activeBtn.classList.remove('text-slate-400', 'border-transparent');
        activeBtn.classList.add('text-blue-700', 'border-blue-700');
    }

    // Modal
    function bukaModal(id) {
        let modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function tutupModal(id) {
        let modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Dropdown
    document.addEventListener("DOMContentLoaded", function() {
        const btnOpsi = document.getElementById('btnOpsiUser');
        const dropdownOpsi = document.getElementById('dropdownOpsiUser');
        const iconOpsi = document.getElementById('iconOpsiUser');

        if (btnOpsi && dropdownOpsi) {
            btnOpsi.addEventListener('click', (e) => {
                e.stopPropagation(); 
                dropdownOpsi.classList.toggle('hidden');
                setTimeout(() => {
                    dropdownOpsi.classList.toggle('opacity-0');
                    dropdownOpsi.classList.toggle('scale-95');
                    iconOpsi.classList.toggle('rotate-180');
                }, 10);
            });

            document.addEventListener('click', (e) => {
                if (!btnOpsi.contains(e.target) && !dropdownOpsi.contains(e.target)) {
                    dropdownOpsi.classList.add('opacity-0', 'scale-95');
                    iconOpsi.classList.remove('rotate-180');
                    setTimeout(() => dropdownOpsi.classList.add('hidden'), 200);
                }
            });
        }
    });
</script>

<script>
    // Fungsi untuk membuka dropdown aksi di tabel
    function toggleAksi(id) {
        document.querySelectorAll('[id^="aksi-"]').forEach(el => {
            if(el.id !== id) el.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }

    // Tutup menu aksi jika klik di luar titik tiga
    document.addEventListener('click', function(event) {
        if (!event.target.closest('td.relative')) {
            document.querySelectorAll('[id^="aksi-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });

</script>

@endsection