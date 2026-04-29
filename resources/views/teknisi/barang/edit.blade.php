@extends('layouts.app')

@section('content')
<style>
    .btn-biru-solid { background-color: #2563eb; color: white; }
    .btn-biru-solid:hover { background-color: #1d4ed8; }
    .btn-putih-outline { background-color: white; border: 1px solid #e2e8f0; color: #475569; }
    .btn-putih-outline:hover { background-color: #f8fafc; color: #0f172a; }
</style>

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('teknisi.barang.index') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Data Barang</h2>
        </div>
        <p class="text-slate-500 text-sm font-medium ml-13">Perbarui informasi untuk <span class="font-bold text-blue-600">{{ $barang->kode_barang }}</span></p>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded-lg shadow-sm flex items-center font-medium">
            <i class="fas fa-exclamation-triangle mr-3"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('teknisi.barang.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-slate-700 text-sm font-bold mb-2">Nama Alat / Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Kategori <span class="text-rose-500">*</span></label>
                        <select name="kategori" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all text-sm appearance-none">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->nama_kategori }}" {{ $barang->kategori == $kat->nama_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Merk / Brand</label>
                        <input type="text" name="merk" value="{{ $barang->merk }}" placeholder="Contoh: Krisbow, dll (Boleh dikosongkan)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-bold mb-2">Stok Total <span class="text-rose-500">*</span></label>
                        <input type="number" name="stok_total" min="1" value="{{ $barang->stok_total }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all text-sm">
                        <p class="text-[11px] text-slate-400 mt-2 font-medium"><i class="fas fa-info-circle mr-1"></i> Stok tersedia saat ini: {{ $barang->stok_tersedia }}</p>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-slate-700 text-sm font-bold mb-2">Keterangan / Kondisi</label>
                        <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan khusus jika ada..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all text-sm">{{ $barang->keterangan }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('teknisi.barang.index') }}" class="btn-putih-outline px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Batal</a>
                    <button type="submit" class="btn-biru-solid px-6 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 shadow-md transition-all">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection