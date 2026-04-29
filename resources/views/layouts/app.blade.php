<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>PUSDAT | SMKN 1 Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-main-content { background-color: #f0f4f8; }
        .sidebar-winter {
            background: linear-gradient(to bottom, #020617 0%, #1e40af 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        .header-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-main-content flex h-screen overflow-hidden text-slate-800">

    <aside class="w-64 sidebar-winter text-white flex flex-col shadow-2xl transition-all duration-300 z-20">
        <div class="h-20 flex items-center justify-center border-b border-white/10 px-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Pusdat" class="max-h-14 w-auto drop-shadow-lg">
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <p class="text-xs font-semibold text-blue-300/60 uppercase tracking-widest mb-3 ml-2">Menu Utama</p>

            @if(auth()->user()->role == 'teknisi')
                <a href="{{ route('teknisi.dashboard') }}" class="{{ request()->routeIs('teknisi.dashboard') ? 'bg-blue-600/40 text-white border-l-4 border-blue-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-home w-5 text-center"></i> <span class="font-medium text-sm">Dashboard</span>
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['koordinator', 'koordinator_teknisi']))
                <a href="/koordinator/dashboard" class="{{ request()->is('koordinator/dashboard') ? 'bg-blue-600/40 text-white border-l-4 border-blue-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-chart-line w-5 text-center"></i> <span class="font-medium text-sm">Pusat Kendali</span>
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['teknisi', 'koordinator', 'koordinator_teknisi']))
                <a href="{{ route('teknisi.users.index') }}" class="{{ request()->routeIs('teknisi.users.*') ? 'bg-blue-600/40 text-white border-l-4 border-blue-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-users w-5 text-center"></i> <span class="font-medium text-sm">Kelola User</span>
                </a>
                
                <a href="{{ route('teknisi.barang.index') }}" class="{{ request()->routeIs('teknisi.barang.*') ? 'bg-blue-600/40 text-white border-l-4 border-blue-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-boxes w-5 text-center"></i> <span class="font-medium text-sm">Data Barang</span>
                </a>

                <div class="pt-4 pb-1">
                    <p class="text-xs font-semibold text-emerald-300/60 uppercase tracking-widest ml-2">Manajemen Sistem</p>
                </div>

                @if(in_array(auth()->user()->role, ['koordinator', 'koordinator_teknisi']))
                    <a href="{{ route('koordinator.peminjaman.index') }}" class="{{ request()->routeIs('koordinator.peminjaman.*') ? 'bg-emerald-600/40 text-white border-l-4 border-emerald-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-clipboard-check w-5 text-center text-emerald-400"></i> 
                        <span class="font-medium text-sm">Pantau Peminjaman</span>
                    </a>
                @else
                    <a href="{{ route('teknisi.peminjaman.index') }}" class="{{ request()->routeIs('teknisi.peminjaman.*') ? 'bg-emerald-600/40 text-white border-l-4 border-emerald-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-clipboard-check w-5 text-center text-emerald-400"></i> 
                        <span class="font-medium text-sm">Verifikasi Surat</span>
                    </a>
                @endif

                    <a href="{{ route('teknisi.pengumuman.index') }}" class="{{ request()->routeIs('teknisi.pengumuman.*') ? 'bg-indigo-600/40 text-white border-l-4 border-indigo-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-bullhorn w-5 text-center text-indigo-400"></i> <span class="font-medium text-sm">Pengumuman</span>
                    </a>

                    @if(auth()->user()->role == 'teknisi')
                    <a href="{{ route('teknisi.sop.index') }}" class="{{ request()->routeIs('teknisi.sop.*') ? 'bg-blue-600/40 text-white border-l-4 border-blue-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-file-signature w-5 text-center text-blue-400"></i> <span class="font-medium text-sm">SOP Peminjaman</span>
                    </a>
                    @endif

                    <a href="{{ route('koordinator.rekapitulasi.index') }}" class="{{ request()->routeIs('koordinator.rekapitulasi.*') ? 'bg-amber-600/40 text-white border-l-4 border-amber-400' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-folder-open w-5 text-center text-amber-400"></i> <span class="font-medium text-sm">Rekapitulasi Sistem</span>
                    </a>
            @endif
        </nav>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="h-20 header-glass shadow-sm flex items-center justify-between px-8 relative z-50">
            <div>
                </div>

            <div class="relative">
                <button id="profileBtn" class="flex items-center gap-3 focus:outline-none hover:bg-slate-100/50 p-2 rounded-xl transition-colors">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    </div>
                    
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-blue-100 flex items-center justify-center text-blue-600 shadow-sm transition-colors">
                        <i class="fas fa-user text-lg"></i>
                    </div>
                    <i class="fas fa-chevron-down text-slate-400 text-xs ml-1 transition-transform duration-300" id="chevronIcon"></i>
                </button>

                 <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform opacity-0 scale-95 transition-all duration-200 z-50">
                    <div class="p-5 border-b border-slate-100 text-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-600 mx-auto mb-2">
                            <i class="fas fa-user text-xl"></i>
                        </div>
                        <p class="font-bold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">NISN/NIP: {{ auth()->user()->nisn_nip }}</p>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="block m-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-xl transition-colors flex items-center gap-3 font-semibold">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8 overflow-y-auto relative">
            <div class="absolute top-0 left-0 w-full h-64 bg-blue-100/50 rounded-b-[100px] -z-10 blur-3xl"></div>
            
            @yield('content') 
            
        </main>
    </div>

    <script>
        const btn = document.getElementById('profileBtn');
        const dropdown = document.getElementById('profileDropdown');
        const chevron = document.getElementById('chevronIcon');

        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
            setTimeout(() => {
                dropdown.classList.toggle('opacity-0');
                dropdown.classList.toggle('scale-95');
                chevron.classList.toggle('rotate-180');
            }, 10);
        });

        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('opacity-0', 'scale-95');
                chevron.classList.remove('rotate-180');
                setTimeout(() => dropdown.classList.add('hidden'), 200);
            }
        });
    </script>
</body>
</html>