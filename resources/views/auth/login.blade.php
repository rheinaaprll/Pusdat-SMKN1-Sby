<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Login - Peminjaman Barang Teknisi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* TEMA WINTER SKY */
        .bg-winter-sky {
            background: linear-gradient(135deg, #020617 0%, #1e40af 55%, #e0f2fe 100%);
        }
        
        .glass-card {
            background: rgba(224, 242, 254, 0.85); 
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        /* Tombol  */
        .btn-custom {
            background: linear-gradient(to right, #0f172a, #2563eb); 
            color: white;
        }
        .btn-custom:hover {
            background: linear-gradient(to right, #1e3a8a, #3b82f6);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
        }

        /* Desain Khusus Simbol "&" */
        .elegant-ampersand {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.3em; 
            color: #1d4ed8; 
            margin: 0 2px;
        }

        .title-nowrap {
            white-space: nowrap;
            font-size: 1.3rem; 
        }

        .rfid-hidden {
            position: absolute;
            left: -9999px;
        }
    </style>
</head>
<body class="bg-winter-sky min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="glass-card p-10 rounded-3xl w-full max-w-lg transform transition-all hover:scale-105 duration-500 relative z-10">
        
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center mb-4 transform transition-transform duration-300 hover:scale-110">
                <img src="{{ asset('images/SMKKUAT.png') }}" alt="Logo SMKN 1 Surabaya" class="w-24 h-auto drop-shadow-md">
            </div>
            
            <h2 class="title-nowrap font-extrabold text-slate-800 tracking-tight">
                Sistem Peminjaman Alat <span class="elegant-ampersand">&amp;</span> Barang
            </h2>
            <p class="text-blue-700 text-xs font-bold mt-1.5 tracking-widest uppercase">Teknisi SMKN 1 Surabaya</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf

            <div class="mb-5">
                <label class="block text-slate-700 text-xs font-bold mb-2 uppercase tracking-wider">Username</label>
                <input class="w-full px-4 py-3 bg-white/60 border-2 border-blue-200 rounded-xl text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" id="nisn_nip" name="nisn_nip" type="text" placeholder="Contoh: 1234567 atau ahmad.khanif">
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 text-xs font-bold mb-2 uppercase tracking-wider">Password</label>
                <input class="w-full px-4 py-3 bg-white/60 border-2 border-blue-200 rounded-xl text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" id="password" name="password" type="password" placeholder="••••••••">
            </div>

            <div class="mt-8">
                <button class="w-full btn-custom font-bold py-3.5 px-4 rounded-xl transform transition-all hover:-translate-y-1 focus:outline-none uppercase tracking-widest text-sm" type="submit">
                    Masuk Sekarang
                </button>
            </div>

            <div class="mt-8 text-center border-t border-blue-200/50 pt-5">
                <p class="text-xs text-slate-500 font-bold flex items-center justify-center gap-2 uppercase tracking-wide">
                    <i class="fas fa-id-card text-blue-500 text-lg"></i>
                    Atau tap ID Card pada reader
                </p>
            </div>
            
            <input type="text" name="rfid" id="rfid_input" class="rfid-hidden" autofocus>
        </form>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            const nisnInput = document.getElementById('nisn_nip');
            const passInput = document.getElementById('password');
            if(e.target !== nisnInput && e.target !== passInput) {
                document.getElementById('rfid_input').focus();
            }
        });
    </script>
</body>
</html>