<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/logo-pusdat.png" type="image/png">
    <title>Riwayat Pengajuan | Teknisi SMKN 1 Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen pb-12">

    <div class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Riwayat Pengajuan</h1>
                <p class="text-xs text-slate-500">Pantau status dan unduh surat Anda.</p>
            </div>
            <a href="{{ route('user.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                <i class="fas fa-rocket"></i> Tata Surya
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 mt-8 space-y-4">
        @if($riwayats->count() > 0)
            @foreach($riwayats as $surat)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-blue-100">
                                {{ str_replace('_', ' ', $surat->jenis_surat) }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="text-slate-800 font-bold">Keperluan: "{{ $surat->alasan }}"</h3>
                        <p class="text-sm text-slate-500 mt-1"><i class="fas fa-boxes mr-1"></i> Peminjaman dari: {{ \Carbon\Carbon::parse($surat->tanggal_pinjam)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($surat->tanggal_kembali)->translatedFormat('d M Y') }}</p>
                    </div>

                    <div class="w-full md:w-auto flex flex-col items-end gap-2 border-t md:border-none pt-4 md:pt-0 border-slate-100">
                        @if($surat->status == 'menunggu_verifikasi')
                            <div class="bg-amber-50 text-amber-600 px-4 py-2 rounded-xl text-sm font-bold border border-amber-200 flex items-center gap-2 w-full md:w-auto justify-center">
                                <i class="fas fa-hourglass-half animate-pulse"></i> Sedang Diproses Teknisi
                            </div>
                        @elseif($surat->status == 'bisa_diunduh')
                            <div class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-sm font-bold border border-emerald-200 flex items-center gap-2 w-full md:w-auto justify-center mb-2">
                                <i class="fas fa-check-circle"></i> Selesai Diverifikasi
                            </div>
                            <a href="{{ asset('storage/' . $surat->file_pdf) }}" target="_blank" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5">
                                <i class="fas fa-file-pdf"></i> Unduh Surat PDF
                            </a>
                            @elseif($surat->status == 'ditolak')
                            <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm border border-red-200 w-full md:w-72">
                                <div class="font-bold mb-1 flex items-center gap-2">
                                    <i class="fas fa-times-circle"></i> Pengajuan Ditolak
                                </div>
                                <div class="text-xs text-red-500 mt-1 pt-1 border-t border-red-100 italic">
                                    "{{ $surat->pesan_penolakan }}"
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Riwayat</h3>
                <p class="text-slate-500 text-sm">Anda belum pernah mengajukan surat peminjaman apapun.</p>
            </div>
        @endif
    </div>

</body>
</html>