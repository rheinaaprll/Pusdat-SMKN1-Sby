@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah User Baru</h2>
            <p class="text-gray-500 text-sm mt-1">Masukkan data dengan lengkap. Password akan digenerate otomatis.</p>
        </div>
        <a href="{{ route('teknisi.users.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
            <ul class="list-disc pl-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('teknisi.users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Budi Santoso">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">NISN / NIP</label>
                    <input type="text" name="nisn_nip" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Masukkan Nomor Induk">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: 08123456789">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sebagai (Role)</label>
                    <select name="role" id="roleSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="" disabled selected>-- Pilih Role --</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="tenaga_pendidik">Tenaga Pendidik</option>
                        <option value="koordinator" {{ (isset($user) && $user->role == 'koordinator') ? 'selected' : '' }}>Koordinator</option>
                        <option value="teknisi" {{ (isset($user) && $user->role == 'teknisi') ? 'selected' : '' }}>Teknisi</option>
                    </select>
                </div>

                <div>
                    <label id="labelKelasUnit" class="block text-sm font-semibold text-gray-700 mb-2">Kelas / Unit Kerja</label>
                    <input type="text" name="kelas_unit_kerja" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: 10 TKJ 1 / Ruang Guru">
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            <div class="flex justify-end gap-3">
                <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">Reset</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md transition-colors">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
        const label = document.getElementById('labelKelasUnit');
        if (this.value === 'siswa') {
            label.innerText = 'Kelas';
        } else if (this.value === 'guru' || this.value === 'tenaga_pendidik') {
            label.innerText = 'Unit Kerja';
        }
    });
</script>
@endsection