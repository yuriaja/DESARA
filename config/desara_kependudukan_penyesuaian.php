
<?php

// Tambahan formulir sesuai rincian 10 layanan kependudukan.

$f = function (
    string $label,
    string $type = 'text',
    bool $required = true,
    array $extra = []
): array {
    return array_merge([
        'label' => $label,
        'type' => $type,
        'required' => $required,
    ], $extra);
};

$d = function (
    string $label,
    bool $required = false,
    array $extra = []
): array {
    return array_merge([
        'label' => $label,
        'required' => $required,
    ], $extra);
};

return [

    // 1. SURAT KETERANGAN DOMISILI
    'Surat Keterangan Domisili' => [
        'fields' => [
            'dusun' => $f('Dusun domisili'),
            'desa' => $f('Desa / kelurahan domisili'),
            'kecamatan' => $f('Kecamatan domisili'),
            'kabupaten' => $f('Kabupaten / kota domisili'),
            'instansi_tujuan' => $f(
                'Instansi tujuan surat',
                'text',
                false
            ),
            'alamat_asal' => $f(
                'Alamat asal jika berbeda',
                'textarea',
                false
            ),
        ],
        'docs' => [
            'ktp' => $d(
                'Foto / scan KTP (jika belum terverifikasi)'
            ),
            'kk' => $d(
                'Foto / scan KK (jika belum terverifikasi)'
            ),
            'pengantar_rt' => $d(
                'Surat Pengantar RT/RW jika disyaratkan desa'
            ),
            'kk_pemilik' => $d(
                'KK pemilik tempat tinggal jika diperlukan'
            ),
            'bukti_tinggal' => $d(
                'Dokumen pendukung domisili jika diperlukan'
            ),
        ],
    ],

    // 2. SURAT KETERANGAN PENDUDUK
    'Surat Keterangan Penduduk' => [
        'fields' => [
            'tercatat' => $f(
                'Apakah tercatat sebagai penduduk desa ini?',
                'select',
                true,
                ['options' => ['Ya', 'Tidak', 'Tidak tahu']]
            ),
            'rt' => $f('RT sesuai alamat terdaftar'),
            'rw' => $f('RW sesuai alamat terdaftar'),
            'dusun' => $f('Dusun tempat tinggal'),
        ],
        'docs' => [
            'ktp' => $d('Foto KTP jika belum terverifikasi'),
            'kk' => $d('Foto KK jika belum terverifikasi'),
            'pengantar_rt' => $d(
                'Surat Pengantar RT/RW jika diwajibkan SOP'
            ),
            'bukti_status' => $d(
                'Bukti pendukung status kependudukan'
            ),
        ],
    ],

    // 3. SURAT KETERANGAN PINDAH DOMISILI
    'Surat Keterangan Pindah Domisili' => [
        'fields' => [
            'jenis' => $f(
                'Jenis kepindahan',
                'select',
                true,
                [
                    'options' => [
                        'Sendiri',
                        'Sebagian keluarga',
                        'Seluruh keluarga'
                    ]
                ]
            ),
            'tanggal_pindah' => $f(
                'Rencana tanggal pindah',
                'date',
                false
            ),
            'jumlah' => $f(
                'Jumlah anggota yang pindah',
                'number'
            ),
            'anggota' => $f(
                'Data anggota keluarga yang pindah',
                'repeat',
                true,
                [
                    'columns' => [
                        'nama' => 'Nama lengkap',
                        'nik' => 'NIK',
                        'hubungan' => 'Hubungan keluarga'
                    ]
                ]
            ),
            'kepala_ikut' => $f(
                'Apakah kepala keluarga ikut pindah?',
                'select',
                true,
                ['options' => ['Ya', 'Tidak']]
            ),
            'keluarga_tinggal' => $f(
                'Apakah ada keluarga yang tetap tinggal?',
                'select',
                true,
                ['options' => ['Ya', 'Tidak']]
            ),
        ],
        'docs' => [
            'kk' => $d('Foto / scan KK asal', true),
            'ktp' => $d(
                'KTP-el atau KIA anggota terkait jika diperlukan'
            ),
            'pendukung' => $d(
                'Dokumen pendukung kepindahan'
            ),
            'surat_kuasa' => $d(
                'Surat kuasa jika diwakilkan'
            ),
            'identitas_kuasa' => $d(
                'Identitas penerima kuasa jika diwakilkan'
            ),
            'pengasuhan' => $d(
                'Persetujuan atau dokumen pengasuhan jika diperlukan'
            ),
        ],
    ],

    // 4. SURAT KETERANGAN DATANG PENDUDUK
    'Surat Keterangan Datang Penduduk' => [
        'fields' => [
            'desa_asal' => $f('Desa / kelurahan asal'),
            'kecamatan_asal' => $f('Kecamatan asal'),
            'kabupaten_asal' => $f('Kabupaten / kota asal'),
            'provinsi_asal' => $f('Provinsi asal'),
            'rt_tujuan' => $f('RT tempat tinggal baru'),
            'rw_tujuan' => $f('RW tempat tinggal baru'),
            'permanen' => $f(
                'Apakah akan menetap permanen?',
                'select',
                true,
                ['options' => ['Ya', 'Tidak']]
            ),
            'hunian' => $f(
                'Jenis tempat tinggal',
                'select',
                true,
                [
                    'options' => [
                        'Milik sendiri',
                        'Sewa / kontrak',
                        'Kos',
                        'Menumpang'
                    ]
                ]
            ),
            'no_kk_tujuan' => $f(
                'Nomor KK keluarga yang ditumpangi',
                'text',
                true,
                [
                    'when' => [
                        'key' => 'numpang_kk',
                        'in' => ['Ya']
                    ]
                ]
            ),
            'anggota_datang' => $f(
                'Data anggota keluarga yang datang',
                'repeat',
                true,
                [
                    'columns' => [
                        'nama' => 'Nama lengkap',
                        'nik' => 'NIK',
                        'hubungan' => 'Hubungan keluarga'
                    ]
                ]
            ),
        ],
        'docs' => [
            'ktp' => $d(
                'KTP-el / KIA lama jika diperlukan'
            ),
            'kk' => $d('KK asal jika diperlukan'),
            'skpwni' => $d(
                'SKPWNI untuk perpindahan yang memerlukannya'
            ),
            'bukti_alamat' => $d(
                'Surat pernyataan tidak keberatan pemilik tempat tinggal'
            ),
            'persetujuan_kk' => $d(
                'Persetujuan kepala keluarga tujuan',
                false,
                [
                    'when' => [
                        'key' => 'numpang_kk',
                        'in' => ['Ya']
                    ]
                ]
            ),
            'pengasuhan' => $d(
                'Dokumen pengasuhan anak jika diperlukan'
            ),
        ],
    ],

    // 5. SURAT KETERANGAN BEDA NAMA / IDENTITAS
    'Surat Keterangan Beda Nama / Identitas' => [
        'fields' => [
            'data_benar' => $f(
                'Data yang dinyatakan benar berdasarkan bukti'
            ),
            'penyebab' => $f(
                'Penyebab perbedaan jika diketahui',
                'textarea',
                false
            ),
            'orang_sama' => $f(
                'Apakah kedua dokumen milik orang yang sama?',
                'select',
                true,
                ['options' => ['Ya', 'Tidak']]
            ),
            'instansi_tujuan' => $f(
                'Instansi tujuan surat',
                'text',
                false
            ),
        ],
        'docs' => [
            'dokumen_1' => $d(
                'Foto / scan dokumen pertama',
                true
            ),
            'dokumen_2' => $d(
                'Foto / scan dokumen kedua',
                true
            ),
            'identitas' => $d(
                'KTP atau KK pembanding jika diperlukan'
            ),
            'bukti_pembanding' => $d(
                'Akta / ijazah / dokumen pembanding lainnya'
            ),
            'pernyataan' => $d(
                'Surat pernyataan satu orang yang sama jika disyaratkan'
            ),
        ],
    ],

    // 6. SURAT KETERANGAN BELUM MEMILIKI KTP
    'Surat Keterangan Belum Memiliki KTP' => [
        'fields' => [
            'untuk_siapa' => $f(
                'Siapa yang membutuhkan surat?',
                'select',
                true,
                ['options' => ['Diri sendiri', 'Orang lain']]
            ),
            'nomor_kk_subjek' => $f(
                'Nomor KK orang yang diterangkan (jika ada)',
                'text',
                false
            ),
            'tempat_lahir' => $f('Tempat lahir'),
            'alamat_subjek' => $f(
                'Alamat orang yang diterangkan',
                'textarea'
            ),
            'tanggal_perekaman' => $f(
                'Tanggal perekaman KTP-el jika diketahui',
                'date',
                false
            ),
            'nama_wali' => $f(
                'Nama orang tua / wali / perwakilan',
                'text',
                true,
                [
                    'when' => [
                        'key' => 'untuk_siapa',
                        'in' => ['Orang lain']
                    ]
                ]
            ),
            'nik_wali' => $f(
                'NIK orang tua / wali / perwakilan',
                'text',
                false,
                [
                    'when' => [
                        'key' => 'untuk_siapa',
                        'in' => ['Orang lain']
                    ]
                ]
            ),
        ],
        'docs' => [
            'kk' => $d('Foto KK jika ada dan diperlukan'),
            'bukti' => $d('Bukti perekaman KTP-el jika ada'),
            'akta' => $d(
                'Akta kelahiran atau identitas pendukung'
            ),
            'ktp_wali' => $d(
                'KTP orang tua / wali jika diperlukan',
                false,
                [
                    'when' => [
                        'key' => 'untuk_siapa',
                        'in' => ['Orang lain']
                    ]
                ]
            ),
        ],
    ],

    // 7. SURAT PENGANTAR PEMBUATAN KTP
    'Surat Pengantar Pembuatan KTP' => [
        'fields' => [
            'jenis' => $f(
                'Jenis permohonan KTP-el',
                'select',
                true,
                [
                    'options' => [
                        'Pertama kali',
                        'Hilang',
                        'Rusak',
                        'Perubahan data',
                        'Pindah datang'
                    ]
                ]
            ),
            'nama_pemilik' => $f('Nama pemilik KTP-el'),
            'nik_pemilik' => $f('NIK pemilik KTP-el'),
            'nomor_kk' => $f(
                'Nomor KK jika sudah memiliki',
                'text',
                false
            ),
            'lokasi_perekaman' => $f(
                'Lokasi perekaman sebelumnya jika diketahui',
                'text',
                false
            ),
            'tanggal_hilang' => $f(
                'Perkiraan tanggal kehilangan',
                'date',
                true,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Hilang']
                    ]
                ]
            ),
        ],
        'docs' => [
            'kk' => $d(
                'Foto KK untuk pencocokan data'
            ),
            'ktp_lama' => $d(
                'Foto KTP-el lama / rusak',
                true,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Rusak', 'Perubahan data']
                    ]
                ]
            ),
            'lapor_hilang' => $d(
                'Surat kehilangan dari kepolisian',
                true,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Hilang']
                    ]
                ]
            ),
            'bukti_perubahan' => $d(
                'Bukti perubahan data',
                true,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Perubahan data']
                    ]
                ]
            ),
            'skpwni' => $d(
                'SKPWNI / KK alamat baru jika diperlukan',
                false,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Pindah datang']
                    ]
                ]
            ),
        ],
    ],

    // 8. SURAT PENGANTAR PEMBUATAN KK
    'Surat Pengantar Pembuatan KK' => [
        'fields' => [
            'jenis' => $f(
                'Jenis permohonan KK',
                'select',
                true,
                [
                    'options' => [
                        'Membentuk keluarga baru',
                        'Pisah KK',
                        'Penggantian kepala keluarga',
                        'Pindah datang',
                        'KK hilang',
                        'KK rusak'
                    ]
                ]
            ),
            'kk_sebelumnya' => $f(
                'Nomor KK sebelumnya jika ada',
                'text',
                false
            ),
            'status_perkawinan' => $f(
                'Status perkawinan calon kepala keluarga',
                'select',
                true,
                [
                    'options' => [
                        'Belum kawin',
                        'Kawin',
                        'Cerai hidup',
                        'Cerai mati'
                    ]
                ]
            ),
            'anggota' => $f(
                'Data anggota keluarga',
                'repeat',
                true,
                [
                    'columns' => [
                        'nama' => 'Nama lengkap',
                        'nik' => 'NIK jika ada',
                        'hubungan' => 'Hubungan dengan kepala keluarga'
                    ]
                ]
            ),
            'menumpang_kk' => $f(
                'Apakah akan menumpang KK lain?',
                'select',
                false,
                ['options' => ['Ya', 'Tidak']]
            ),
            'kk_tujuan' => $f(
                'Nomor KK tujuan',
                'text',
                true,
                [
                    'when' => [
                        'key' => 'menumpang_kk',
                        'in' => ['Ya']
                    ]
                ]
            ),
        ],
        'docs' => [
            'ktp' => $d('Identitas pemohon jika diperlukan'),
            'kk_lama' => $d('KK lama jika ada'),
            'nikah' => $d(
                'Buku nikah / akta perkawinan atau dokumen status keluarga',
                false,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Membentuk keluarga baru']
                    ]
                ]
            ),
            'skpwni' => $d(
                'SKPWNI jika diperlukan',
                false,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Pindah datang']
                    ]
                ]
            ),
            'hilang' => $d(
                'Surat kehilangan dari kepolisian',
                true,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['KK hilang']
                    ]
                ]
            ),
            'kk_rusak' => $d(
                'Foto / scan KK yang rusak',
                false,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['KK rusak']
                    ]
                ]
            ),
            'akta_kematian' => $d(
                'Akta kematian kepala keluarga',
                false,
                [
                    'when' => [
                        'key' => 'jenis',
                        'in' => ['Penggantian kepala keluarga']
                    ]
                ]
            ),
            'pengasuhan' => $d(
                'Persetujuan menumpang / dokumen pengasuhan jika diperlukan'
            ),
        ],
    ],

    // 9. SURAT PENGANTAR PERUBAHAN DATA KK
    'Surat Pengantar Perubahan Data KK' => [
        'fields' => [
            'dasar_perubahan' => $f(
                'Dokumen yang menjadi dasar perubahan',
                'select',
                true,
                [
                    'options' => [
                        'Akta kelahiran',
                        'Ijazah',
                        'Buku nikah / akta perkawinan',
                        'Akta perceraian',
                        'Akta kematian',
                        'SKPWNI',
                        'Dokumen sah lainnya'
                    ]
                ]
            ),
            'tanggal_peristiwa' => $f(
                'Tanggal peristiwa / perubahan jika relevan',
                'date',
                false
            ),
        ],
        'docs' => [
            'kk' => $d('Foto / scan KK lama', true),
            'ktp' => $d('Foto KTP jika diperlukan'),
            'bukti' => $d(
                'Dokumen bukti perubahan sesuai kasus',
                true
            ),
        ],
    ],

    // 10. SURAT KETERANGAN KEHILANGAN DOKUMEN
    'Surat Keterangan atau Pengantar Kehilangan Dokumen' => [
        'fields' => [
            'nik_pemilik' => $f(
                'NIK pemilik dokumen jika memiliki',
                'text',
                false
            ),
            'jenis' => $f(
                'Jenis dokumen yang hilang',
                'select',
                true,
                [
                    'options' => [
                        'KTP-el',
                        'Kartu Keluarga',
                        'Akta kelahiran',
                        'Dokumen kependudukan lainnya'
                    ]
                ]
            ),
            'nomor_laporan' => $f(
                'Nomor laporan polisi jika sudah ada',
                'text',
                false,
                [
                    'when' => [
                        'key' => 'lapor',
                        'in' => ['Ya']
                    ]
                ]
            ),
            'diwakilkan' => $f(
                'Apakah pengajuan diwakilkan?',
                'select',
                true,
                ['options' => ['Ya', 'Tidak']]
            ),
            'nama_perwakilan' => $f(
                'Nama perwakilan',
                'text',
                true,
                [
                    'when' => [
                        'key' => 'diwakilkan',
                        'in' => ['Ya']
                    ]
                ]
            ),
        ],
        'docs' => [
            'salinan' => $d(
                'Salinan dokumen yang hilang jika masih ada'
            ),
            'laporan' => $d(
                'Surat kehilangan kepolisian jika tersedia'
            ),
            'identitas' => $d(
                'Foto KTP / KK pemohon jika belum terverifikasi'
            ),
            'surat_kuasa' => $d(
                'Surat kuasa jika diwakilkan',
                false,
                [
                    'when' => [
                        'key' => 'diwakilkan',
                        'in' => ['Ya']
                    ]
                ]
            ),
        ],
    ],

];