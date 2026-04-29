 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Pusat Layanan | Teknisi </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; overflow: hidden; }

        .theme-crystal { 
            background: radial-gradient(circle at center, #1e3a8a 0%, #172554 50%, #0f172a 100%); 
            min-height: 100vh;
            position: relative;
        }

        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            pointer-events: none;
        }

        /* Container Tata Surya */
        .solar-container {
            position: absolute;
            top: 55%; 
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            width: 100%;
            height: 100%;
        }

        .orbit-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px dashed rgba(147, 197, 253, 0.3);
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
            z-index: 5;
            width: 460px; /* Sedikit dikecilkan */
            height: 460px;
        }
        .solar-container.expanded .orbit-ring {
            opacity: 1;
            transform: scale(1);
            animation: spin 50s linear infinite;
        }

        .sun {
            position: relative;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            z-index: 30;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 60px rgba(96, 165, 250, 0.4), inset 0 0 20px rgba(147, 197, 253, 0.5);
            cursor: pointer;
            transition: all 0.4s ease;
            border: 8px solid #ffffff;
            width: 220px; 
            height: 220px;
        }
        .sun img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            animation: float-img 4s ease-in-out infinite;
        }

        .planet {
            position: absolute;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #0f172a; 
            font-weight: 800;
            text-align: center;
            background: #ffffff; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            
            --tx: 0px; --ty: 0px; --scale: 0;
            transform: translate(var(--tx), var(--ty)) scale(var(--scale));
            opacity: 0;
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 20;
            width: 120px; 
            height: 120px;
            font-size: 0.75rem;
        }
        .planet i { font-size: 1.8rem; margin-bottom: 6px; }

        /* Jarak Orbit */
        .solar-container.expanded .planet-top { --ty: -230px; --scale: 1; opacity: 1; }
        .solar-container.expanded .planet-right { --tx: 230px; --scale: 1; opacity: 1; }
        .solar-container.expanded .planet-bottom { --ty: 230px; --scale: 1; opacity: 1; }
        .solar-container.expanded .planet-left { --tx: -230px; --scale: 1; opacity: 1; }

        @media (max-width: 640px) {
            .sun { width: 160px; height: 160px; border-width: 5px; }
            .sun img { width: 80px; height: 80px; }
            .planet { width: 95px; height: 95px; font-size: 0.65rem; }
            .orbit-ring { width: 300px; height: 300px; }
            .solar-container.expanded .planet-top { --ty: -150px; }
            .solar-container.expanded .planet-right { --tx: 150px; }
            .solar-container.expanded .planet-bottom { --ty: 150px; }
            .solar-container.expanded .planet-left { --tx: -150px; }
            .solar-container { top: 60%; } /* Turun sedikit di HP */
        }

        .planet-top i { color: #1d4ed8; } 
        .planet-right i { color: #047857; } 
        .planet-bottom i { color: #4338ca; } 
        .planet-left i { color: #b45309; } 

        .solar-container.expanded .planet:hover { --scale: 1.1; z-index: 40; background: #f8fafc; }

        @keyframes float-img { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        @keyframes fade-in-down { 0% { opacity: 0; transform: translateY(-20px) translateX(-50%); } 100% { opacity: 1; transform: translateY(0) translateX(-50%); } }
    </style>
</head>

<body class="theme-crystal">
    <div class="glow-orb bg-blue-400/20 w-[500px] h-[500px] top-[-10%] left-[-10%]"></div>
    <div class="glow-orb bg-indigo-500/20 w-[600px] h-[600px] bottom-[-20%] right-[-10%]"></div>

    <div class="absolute top-10 left-1/2 -translate-x-1/2 text-center w-full px-4 z-20" style="animation: fade-in-down 1s ease-out forwards;">
        <h1 class="text-2xl md:text-4xl font-light text-white tracking-wide drop-shadow-lg">
            Selamat Datang, <span class="font-bold text-blue-300">{{ auth()->user()->name }}</span>
        </h1>
        <p class="text-blue-200/80 text-xs md:text-sm mt-2 uppercase tracking-[0.3em] font-semibold">
            Layanan Peminjaman ALat dan Barang Teknisi SMKN 1 Surabaya
        </p>
    </div>

    @if(session('success'))
        <div id="notifSuccess" class="fixed top-28 left-1/2 -translate-x-1/2 z- bg-emerald-500/90 backdrop-blur text-white px-8 py-3 rounded-2xl shadow-2xl flex items-center gap-4 animate-bounce border border-emerald-400">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        <script>setTimeout(() => { document.getElementById('notifSuccess').remove(); }, 5000);</script>
    @endif

    @if($pengumuman)
    <div id="announcementModal" class="fixed inset-0 flex items-center justify-center px-4" style="z-index: 9999;">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl p-8 max-w-lg w-full">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl border border-blue-100">
                    <i class="fas fa-bullhorn animate-bounce"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4">Pengumuman Penting</h3>
                <p class="text-slate-600 leading-relaxed mb-8">"{{ $pengumuman->isi_pengumuman }}"</p>
                <button disabled id="btnClose" class="w-full bg-slate-100 text-slate-400 py-3 rounded-xl font-bold transition-all cursor-not-allowed">
                    Tunggu <span id="timer">3</span> detik...
                </button>
            </div>
        </div>
    </div>
    @endif

    <div id="profileModal" class="fixed inset-0 hidden items-center justify-center px-4 opacity-0 transition-opacity duration-300" style="z-index: 9999;">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeProfile()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full text-center transform scale-95 transition-transform duration-300" id="profileBox">
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl border-4 border-blue-100">
                <i class="fas fa-user-circle"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h3>
            <p class="text-slate-500 font-medium text-sm mb-6">NISN/NIP: {{ auth()->user()->nisn_nip }}</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-sign-out-alt"></i> Keluar (Logout)
                </button>
            </form>
            <button type="button" onclick="closeProfile()" class="mt-4 text-sm text-slate-400 hover:text-slate-600 font-medium">Tutup Jendela</button>
        </div>
    </div>

    <div class="solar-container" id="solarSystem">
        <div class="orbit-ring"></div>

        <div class="planet planet-top" onclick="openProfile()">
            <i class="fas fa-user-circle"></i>
            <span>PROFIL</span>
        </div>

        <a href="{{ route('user.peminjaman.index') }}" class="planet planet-right">
            <i class="fas fa-boxes"></i>
            <span>PEMINJAMAN</span>
        </a>

        <a href="{{ route('user.riwayat') }}" class="planet planet-bottom">
            <i class="fas fa-history"></i>
            <span>RIWAYAT</span>
        </a>

        <a href="{{ route('user.pengembalian') }}" class="planet planet-left">
            <i class="fas fa-undo-alt"></i>
            <span>PENGEMBALIAN</span>
        </a>

        <div class="sun" onclick="toggleSolarSystem()">
            <img src="{{ asset('images/smkn1-logo.png') }}" alt="Logo SMKN 1">
            <p class="text-[11px] font-black text-slate-800 mt-2 uppercase tracking-widest text-center">Tap di Sini</p>
        </div>
    </div>

    <div class="absolute bottom-8 right-10 flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
        <span class="text-xs md:text-sm font-bold text-blue-200 uppercase tracking-widest drop-shadow-md">MANAGED BY</span>
        <img src="{{ asset('images/logo.png') }}" class="h-10 md:h-12 w-auto grayscale brightness-200 drop-shadow-lg" alt="Teknisi">
    </div>

    <script>
        function toggleSolarSystem() { document.getElementById('solarSystem').classList.toggle('expanded'); }
        function openProfile() {
            const modal = document.getElementById('profileModal');
            const box = document.getElementById('profileBox');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modal.classList.remove('opacity-0'); box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
        }
        function closeProfile() {
            const modal = document.getElementById('profileModal');
            const box = document.getElementById('profileBox');
            modal.classList.add('opacity-0'); box.classList.remove('scale-100'); box.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }
        @if($pengumuman)
        let timeLeft = 3;
        const countdown = setInterval(() => {
            timeLeft--; document.getElementById('timer').innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdown);
                const btn = document.getElementById('btnClose');
                btn.disabled = false; btn.innerText = "Tutup Pengumuman";
                btn.classList.replace('bg-slate-100', 'bg-blue-600'); btn.classList.replace('text-slate-400', 'text-white');
                btn.onclick = () => { document.getElementById('announcementModal').classList.add('hidden'); };
            }
        }, 1000);
        @endif
    </script>
</body>
</html>