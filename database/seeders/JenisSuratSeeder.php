<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /** Aman dijalankan berulang: baris dicocokkan lewat kode_surat. */
    public function run(): void
    {
        $daftar = [
            'KET' => 'Surat Keterangan',
            'KEM' => 'Surat Kematian',
            'PDH' => 'Surat Pengantar Pindah',
            'BTN' => 'Mengajukan Bantuan',
            'REK' => 'Surat Permohonan Membuka Rekening',
        ];

        foreach ($daftar as $kode => $nama) {
            JenisSurat::updateOrCreate(['kode_surat' => $kode], ['nama_surat' => $nama]);
        }
    }
}