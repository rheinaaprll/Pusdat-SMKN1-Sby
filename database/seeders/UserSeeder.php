<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // akun untuk percobaan: Koordinator, Teknisi, dan Siswa
        DB::table('users')->insert([
            [
                'name' => 'Bapak Koordinator',
                'nisn_nip' => '111111',
                'rfid' => '1234567890', 
                'password' => Hash::make('password123'), 
                'no_hp' => '081234567890',
                'kelas_unit_kerja' => 'Ketua Teknisi',
                'role' => 'koordinator',
            ],
            [
                'name' => 'Mas Teknisi',
                'nisn_nip' => '222222',
                'rfid' => '0987654321',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567891',
                'kelas_unit_kerja' => 'Ruang Teknisi',
                'role' => 'teknisi',
            ],
            [
                'name' => 'Budi Siswa',
                'nisn_nip' => '333333',
                'rfid' => '1122334455',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567892',
                'kelas_unit_kerja' => '10 TKJ 1',
                'role' => 'siswa',
            ]
        ]);
    }
}