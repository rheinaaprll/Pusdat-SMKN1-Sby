<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

//'WithHeadingRow', untuk sistem tahu baris pertama Excel adalah Judul Kolom
class UsersImport implements ToCollection, WithHeadingRow
{
    public $akunBaru = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['nama']) || empty($row['nisn_nip'])) {
                continue;
            }

            // Cek NISN sudah terdaftar agar tidak terjadi error bentrok atau duplikat
            $cekUser = User::where('nisn_nip', $row['nisn_nip'])->first();

            if (!$cekUser) {
                // Generate Password Acak 8 Karakter
                $passwordAcak = Str::random(8);
                User::create([
                    'name'             => $row['nama'],
                    'nisn_nip'         => $row['nisn_nip'],
                    'role'             => strtolower($row['role'] ?? 'siswa'),
                    'no_hp'            => $row['no_hp'] ?? null,
                    'kelas_unit_kerja' => $row['kelas_unit_kerja'] ?? '-',
                    'password'         => Hash::make($passwordAcak),
                ]);

                $this->akunBaru[] = [
                    'nama'     => $row['nama'],
                    'nisn_nip' => $row['nisn_nip'],
                    'password' => $passwordAcak
                ];
            }
        }
    }
}