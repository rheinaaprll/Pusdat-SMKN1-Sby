@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Data User</h2>
            <p class="text-gray-500 text-sm mt-1">Ubah informasi yang diperlukan.</p>
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
        <form action="{{ route('teknisi.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') 

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">NISN / NIP</label>
                    <input type="text" name="nisn_nip" value="{{ $user->nisn_nip }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ $user->no_hp }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sebagai (Role)</label>
                    <select name="role" id="roleSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                        <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="tenaga_pendidik" {{ $user->role == 'tenaga_pendidik' ? 'selected' : '' }}>Tenaga Pendidik</option>
                        <option value="koordinator" {{ (isset($user) && $user->role == 'koordinator') ? 'selected' : '' }}>Koordinator</option>
                        <option value="teknisi" {{ (isset($user) && $user->role == 'teknisi') ? 'selected' : '' }}>Teknisi</option>
                    </select>
                </div>

                <div>
                    <label id="labelKelasUnit" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $user->role == 'siswa' ? 'Kelas' : 'Unit Kerja' }}
                    </label>
                    <input type="text" name="kelas_unit_kerja" value="{{ $user->kelas_unit_kerja }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                </div>

                <div class="col-span-2 mt-2" id="rfidContainer">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor RFID (Tap Kartu Scanner di Sini)</label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-4 top-3 text-gray-400"></i>
                        <input type="text" name="rfid" value="{{ $user->rfid }}" placeholder="Klik kotak ini, lalu tap kartu..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all font-mono">
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1 italic">*Hapus nomor di atas dan biarkan kosong jika ingin mencabut akses kartu RFID siswa ini.</p>
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            <div class="flex justify-end gap-3">
                <button type="submit" class="px-6 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-medium shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const label = document.getElementById('labelKelasUnit');
        const rfidContainer = document.getElementById('rfidContainer');

        function aturTampilanForm() {
            if (roleSelect.value === 'siswa') {
                label.innerText = 'Kelas';
            } else {
                label.innerText = 'Unit Kerja';
            }
            rfidContainer.style.display = 'block'; 
        }
        aturTampilanForm();
        // Jalankan setiap kali Teknisi mengganti pilihan Role
        roleSelect.addEventListener('change', aturTampilanForm);
    });
</script>
@endsection