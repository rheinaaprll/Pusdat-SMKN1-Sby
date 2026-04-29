@extends('layouts.app')

@section('content')

<style>
    .btn-simpan-aman {
        background-color: #2563eb; 
        color: white;
    }
    .btn-simpan-aman:hover {
        background-color: #1d4ed8;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4);
    }
</style>

<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">Tambah Inventaris Baru</h2>
            <p class="text-slate-500 text-sm mt-1">Sistem akan membuatkan <span class="font-bold text-blue-600">Kode Barang Otomatis</span> berdasarkan Kategori.</p>
        </div>
        <a href="{{ route('teknisi.barang.index') }}" class="text-slate-500 hover:text-blue-600 font-bold transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('teknisi.barang.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-slate-700 text-sm font-bold mb-2">Nama Alat / Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" required placeholder="Contoh: Bor Listrik Bosch" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-sm">
                </div>

                <div>
                    <label class="block text-slate-700 text-sm font-bold mb-2">Kategori <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="kategori" required class="...">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Peminjaman <span class="text-rose-500">*</span></label>
                    <select name="jenis_peminjaman" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="umum">Barang Umum (Tanpa Surat)</option>
                        <option value="tercatat">Barang Tercatat (Wajib Surat)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-slate-700 text-sm font-bold mb-2">Merek (Opsional)</label>
                    <input type="text" name="merk" placeholder="Contoh: Asus, Bosch, dll" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-sm">
                </div>

                <div>
                    <label class="block text-slate-700 text-sm font-bold mb-2">Jumlah / Stok Total <span class="text-red-500">*</span></label>
                    <input type="number" name="stok_total" required min="1" placeholder="Contoh: 10" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-sm">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-slate-700 text-sm font-bold mb-2">Keterangan Tambahan (Opsional)</label>
                <textarea name="keterangan" rows="3" placeholder="Contoh: Bor ini harus dipinjam bersamaan dengan mata bor set." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-sm"></textarea>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="btn-simpan-aman py-3 px-8 rounded-xl font-bold transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection