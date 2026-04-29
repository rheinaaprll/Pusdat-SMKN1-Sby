<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RfidImport implements ToCollection, WithHeadingRow
{
    public $jumlahBerhasil = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Kolom A bernama 'nisn_nip', Kolom B bernama 'rfid'
            if (empty($row['nisn_nip']) || empty($row['rfid'])) {
                continue;
            }

            // Cari user berdasarkan NISN/NIP, lalu update kolom RFID-nya
            $user = User::where('nisn_nip', $row['nisn_nip'])->first();
            if ($user) {
                $user->update(['rfid' => $row['rfid']]);
                $this->jumlahBerhasil++;
            }
        }
    }
}