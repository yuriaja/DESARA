<?php

$layanan = json_decode(<<<'JSON'
{
  "Surat Keterangan Domisili": {
    "icon": "🏠",
    "summary": "Keterangan tempat tinggal aktual warga.",
    "fields": [
      {
        "key": "alamat_domisili",
        "label": "Alamat tempat tinggal saat ini",
        "type": "textarea",
        "required": true
      },
      {
        "key": "rt",
        "label": "RT domisili",
        "type": "text",
        "required": true
      },
      {
        "key": "rw",
        "label": "RW domisili",
        "type": "text",
        "required": true
      },
      {
        "key": "tanggal_mulai",
        "label": "Tanggal mulai tinggal",
        "type": "date",
        "required": true
      },
      {
        "key": "status_hunian",
        "label": "Status tempat tinggal",
        "type": "select",
        "required": true,
        "options": [
          "Milik sendiri",
          "Kontrak / sewa",
          "Kos",
          "Menumpang keluarga",
          "Lainnya"
        ]
      },
      {
        "key": "pemilik",
        "label": "Nama pemilik / penanggung jawab tempat tinggal",
        "type": "text",
        "required": true,
        "when": {
          "key": "status_hunian",
          "in": [
            "Kontrak / sewa",
            "Kos",
            "Menumpang keluarga"
          ]
        }
      },
      {
        "key": "alamat_asal",
        "label": "Alamat asal sebelumnya",
        "type": "textarea",
        "required": false
      },
      {
        "key": "keperluan",
        "label": "Keperluan surat",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "ktp",
        "label": "Foto / scan KTP",
        "required": true
      },
      {
        "key": "kk",
        "label": "Foto / scan Kartu Keluarga",
        "required": true
      },
      {
        "key": "bukti_tinggal",
        "label": "Bukti tempat tinggal / pengantar RT-RW",
        "required": false
      }
    ]
  },
  "Surat Keterangan Penduduk": {
    "icon": "👥",
    "summary": "Permohonan keterangan warga yang tercatat di desa.",
    "fields": [
      {
        "key": "status_penduduk",
        "label": "Status kependudukan",
        "type": "select",
        "required": true,
        "options": [
          "Penduduk tetap",
          "Penduduk sementara",
          "Lainnya"
        ]
      },
      {
        "key": "alamat_terdaftar",
        "label": "Alamat terdaftar",
        "type": "textarea",
        "required": true
      },
      {
        "key": "sejak",
        "label": "Terdaftar sejak (jika diketahui)",
        "type": "date",
        "required": false
      },
      {
        "key": "instansi",
        "label": "Instansi tujuan (jika ada)",
        "type": "text",
        "required": false
      },
      {
        "key": "keperluan",
        "label": "Keperluan surat",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "ktp",
        "label": "Foto / scan KTP",
        "required": true
      },
      {
        "key": "kk",
        "label": "Foto / scan KK",
        "required": true
      }
    ]
  },
  "Surat Keterangan Pindah Domisili": {
    "icon": "🚚",
    "summary": "Rancangan berdasarkan contoh pengantar pindah Desa Milangasri (bukan penerbitan SKPWNI).",
    "fields": [
      {
        "key": "no_kk",
        "label": "Nomor Kartu Keluarga",
        "type": "text",
        "required": true
      },
      {
        "key": "kepala_keluarga",
        "label": "Nama kepala keluarga",
        "type": "text",
        "required": true
      },
      {
        "key": "asal",
        "label": "Alamat asal lengkap",
        "type": "textarea",
        "required": true
      },
      {
        "key": "tujuan",
        "label": "Alamat tujuan lengkap",
        "type": "textarea",
        "required": true
      },
      {
        "key": "provinsi_tujuan",
        "label": "Provinsi tujuan",
        "type": "text",
        "required": true
      },
      {
        "key": "kab_tujuan",
        "label": "Kabupaten / kota tujuan",
        "type": "text",
        "required": true
      },
      {
        "key": "kecamatan_tujuan",
        "label": "Kecamatan tujuan",
        "type": "text",
        "required": true
      },
      {
        "key": "desa_tujuan",
        "label": "Desa / kelurahan tujuan",
        "type": "text",
        "required": true
      },
      {
        "key": "tanggal_pindah",
        "label": "Rencana tanggal pindah",
        "type": "date",
        "required": true
      },
      {
        "key": "alasan",
        "label": "Alasan pindah",
        "type": "select",
        "required": true,
        "options": [
          "Pekerjaan",
          "Pendidikan",
          "Kesehatan",
          "Keamanan",
          "Perumahan",
          "Keluarga",
          "Lainnya"
        ]
      },
      {
        "key": "alasan_lain",
        "label": "Jelaskan alasan pindah",
        "type": "text",
        "required": true,
        "when": {
          "key": "alasan",
          "in": [
            "Lainnya"
          ]
        }
      },
      {
        "key": "jenis",
        "label": "Jenis kepindahan",
        "type": "select",
        "required": true,
        "options": [
          "Sendiri",
          "Bersama keluarga"
        ]
      },
      {
        "key": "jumlah",
        "label": "Jumlah anggota keluarga ikut pindah",
        "type": "number",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "Bersama keluarga"
          ]
        }
      },
      {
        "key": "anggota",
        "label": "Daftar nama, NIK, dan hubungan keluarga yang ikut pindah",
        "type": "textarea",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "Bersama keluarga"
          ]
        },
        "help": "Satu orang per baris: Nama — NIK — hubungan dalam KK"
      },
      {
        "key": "status_kk",
        "label": "Rencana status KK setelah pindah",
        "type": "select",
        "required": true,
        "options": [
          "Menumpang KK",
          "Membuat KK baru",
          "Nomor KK tetap",
          "Belum tahu"
        ]
      },
      {
        "key": "keperluan",
        "label": "Keterangan tambahan / keperluan",
        "type": "textarea",
        "required": false
      }
    ],
    "docs": [
      {
        "key": "kk",
        "label": "Foto / scan KK lama",
        "required": true
      },
      {
        "key": "ktp",
        "label": "Foto / scan KTP pemohon",
        "required": true
      },
      {
        "key": "pendukung",
        "label": "Dokumen kepindahan pendukung (jika ada)",
        "required": false
      }
    ]
  },
  "Surat Keterangan Datang Penduduk": {
    "icon": "📍",
    "summary": "Pendataan pendatang baru dan alamat tujuan di desa.",
    "fields": [
      {
        "key": "alamat_asal",
        "label": "Alamat asal lengkap",
        "type": "textarea",
        "required": true
      },
      {
        "key": "alamat_tujuan",
        "label": "Alamat tujuan di desa",
        "type": "textarea",
        "required": true
      },
      {
        "key": "tanggal_datang",
        "label": "Tanggal kedatangan",
        "type": "date",
        "required": true
      },
      {
        "key": "alasan_datang",
        "label": "Alasan kepindahan",
        "type": "textarea",
        "required": true
      },
      {
        "key": "hunian",
        "label": "Status hunian",
        "type": "select",
        "required": true,
        "options": [
          "Milik sendiri",
          "Kontrak",
          "Menumpang"
        ]
      },
      {
        "key": "numpang_kk",
        "label": "Menumpang Kartu Keluarga?",
        "type": "select",
        "required": true,
        "options": [
          "Ya",
          "Tidak"
        ]
      },
      {
        "key": "nama_kk",
        "label": "Nama kepala keluarga yang ditumpangi",
        "type": "text",
        "required": true,
        "when": {
          "key": "numpang_kk",
          "in": [
            "Ya"
          ]
        }
      },
      {
        "key": "nik_kk",
        "label": "NIK kepala keluarga yang ditumpangi",
        "type": "text",
        "required": true,
        "when": {
          "key": "numpang_kk",
          "in": [
            "Ya"
          ]
        }
      },
      {
        "key": "jumlah_datang",
        "label": "Jumlah anggota keluarga yang datang",
        "type": "number",
        "required": true
      },
      {
        "key": "anggota_datang",
        "label": "Nama dan NIK anggota yang datang",
        "type": "textarea",
        "required": false
      },
      {
        "key": "no_skpwni",
        "label": "Nomor SKPWNI (jika ada)",
        "type": "text",
        "required": false
      }
    ],
    "docs": [
      {
        "key": "ktp",
        "label": "Foto / scan KTP",
        "required": true
      },
      {
        "key": "kk",
        "label": "Foto / scan KK",
        "required": true
      },
      {
        "key": "skpwni",
        "label": "Foto / scan SKPWNI",
        "required": false
      },
      {
        "key": "bukti_alamat",
        "label": "Bukti / persetujuan alamat tujuan",
        "required": false
      }
    ]
  },
  "Surat Keterangan Beda Nama / Identitas": {
    "icon": "🪪",
    "summary": "Penjelasan perbedaan identitas di antara dua dokumen.",
    "fields": [
      {
        "key": "elemen",
        "label": "Elemen yang berbeda",
        "type": "select",
        "required": true,
        "options": [
          "Nama",
          "Tempat lahir",
          "Tanggal lahir",
          "Lainnya"
        ]
      },
      {
        "key": "dok_1",
        "label": "Jenis dokumen pertama",
        "type": "select",
        "required": true,
        "options": [
          "KTP",
          "KK",
          "Ijazah",
          "Akta kelahiran",
          "Dokumen lainnya"
        ]
      },
      {
        "key": "isi_1",
        "label": "Isi data pada dokumen pertama",
        "type": "text",
        "required": true
      },
      {
        "key": "dok_2",
        "label": "Jenis dokumen kedua",
        "type": "select",
        "required": true,
        "options": [
          "KTP",
          "KK",
          "Ijazah",
          "Akta kelahiran",
          "Dokumen lainnya"
        ]
      },
      {
        "key": "isi_2",
        "label": "Isi data pada dokumen kedua",
        "type": "text",
        "required": true
      },
      {
        "key": "penjelasan",
        "label": "Penjelasan perbedaan",
        "type": "textarea",
        "required": true
      },
      {
        "key": "keperluan",
        "label": "Keperluan surat",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "dokumen_1",
        "label": "Foto / scan dokumen pertama",
        "required": true
      },
      {
        "key": "dokumen_2",
        "label": "Foto / scan dokumen kedua",
        "required": true
      }
    ]
  },
  "Surat Keterangan Belum Memiliki KTP": {
    "icon": "🪪",
    "summary": "Permohonan keterangan kondisi kepemilikan KTP, setelah verifikasi petugas.",
    "fields": [
      {
        "key": "nama_subjek",
        "label": "Nama orang yang diterangkan",
        "type": "text",
        "required": true
      },
      {
        "key": "nik_subjek",
        "label": "NIK (jika sudah ada)",
        "type": "text",
        "required": false
      },
      {
        "key": "lahir",
        "label": "Tanggal lahir",
        "type": "date",
        "required": true
      },
      {
        "key": "alasan",
        "label": "Alasan belum memiliki KTP",
        "type": "select",
        "required": true,
        "options": [
          "Belum cukup usia",
          "Belum perekaman",
          "Sudah perekaman, belum menerima",
          "Lainnya"
        ]
      },
      {
        "key": "perekaman",
        "label": "Sudah perekaman KTP-el?",
        "type": "select",
        "required": true,
        "options": [
          "Ya",
          "Tidak",
          "Tidak tahu"
        ]
      },
      {
        "key": "keperluan",
        "label": "Keperluan keterangan",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "kk",
        "label": "Foto / scan KK",
        "required": true
      },
      {
        "key": "bukti",
        "label": "Bukti perekaman KTP-el (jika ada)",
        "required": false
      }
    ]
  },
  "Surat Pengantar Pembuatan KTP": {
    "icon": "🪪",
    "summary": "Bantuan pengurusan KTP-el; pengantar desa tidak selalu diperlukan.",
    "fields": [
      {
        "key": "jenis",
        "label": "Jenis permohonan KTP-el",
        "type": "select",
        "required": true,
        "options": [
          "Pertama kali",
          "Hilang",
          "Rusak",
          "Perubahan data",
          "Cetak ulang"
        ]
      },
      {
        "key": "perekaman",
        "label": "Sudah pernah perekaman?",
        "type": "select",
        "required": true,
        "options": [
          "Ya",
          "Tidak",
          "Tidak tahu"
        ]
      },
      {
        "key": "data_lama",
        "label": "Data sebelum perubahan",
        "type": "textarea",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "Perubahan data"
          ]
        }
      },
      {
        "key": "data_baru",
        "label": "Data setelah perubahan",
        "type": "textarea",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "Perubahan data"
          ]
        }
      },
      {
        "key": "keperluan",
        "label": "Keterangan / alasan pengurusan",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "kk",
        "label": "Foto / scan KK",
        "required": true
      },
      {
        "key": "ktp_lama",
        "label": "Foto / scan KTP lama",
        "required": false,
        "when": {
          "key": "jenis",
          "in": [
            "Rusak",
            "Perubahan data",
            "Cetak ulang"
          ]
        }
      },
      {
        "key": "lapor_hilang",
        "label": "Foto surat kehilangan dari kepolisian",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "Hilang"
          ]
        }
      },
      {
        "key": "bukti_perubahan",
        "label": "Foto / scan bukti perubahan data",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "Perubahan data"
          ]
        }
      }
    ]
  },
  "Surat Pengantar Pembuatan KK": {
    "icon": "📋",
    "summary": "Bantuan pengurusan penerbitan Kartu Keluarga.",
    "fields": [
      {
        "key": "jenis",
        "label": "Jenis permohonan KK",
        "type": "select",
        "required": true,
        "options": [
          "KK baru setelah menikah",
          "Pisah KK",
          "Pindah datang",
          "KK hilang",
          "KK rusak"
        ]
      },
      {
        "key": "kepala",
        "label": "Nama calon kepala keluarga",
        "type": "text",
        "required": true
      },
      {
        "key": "nik_kepala",
        "label": "NIK calon kepala keluarga",
        "type": "text",
        "required": true
      },
      {
        "key": "alamat",
        "label": "Alamat KK yang diajukan",
        "type": "textarea",
        "required": true
      },
      {
        "key": "jumlah",
        "label": "Jumlah anggota keluarga",
        "type": "number",
        "required": true
      },
      {
        "key": "anggota",
        "label": "Daftar nama dan NIK anggota keluarga",
        "type": "textarea",
        "required": true
      },
      {
        "key": "alasan",
        "label": "Alasan permohonan",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "ktp",
        "label": "Foto / scan KTP pemohon",
        "required": true
      },
      {
        "key": "kk_lama",
        "label": "Foto / scan KK lama",
        "required": false
      },
      {
        "key": "nikah",
        "label": "Foto / scan buku nikah / akta perkawinan",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "KK baru setelah menikah"
          ]
        }
      },
      {
        "key": "skpwni",
        "label": "Foto / scan SKPWNI",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "Pindah datang"
          ]
        }
      },
      {
        "key": "hilang",
        "label": "Foto surat kehilangan kepolisian",
        "required": true,
        "when": {
          "key": "jenis",
          "in": [
            "KK hilang"
          ]
        }
      }
    ]
  },
  "Surat Pengantar Perubahan Data KK": {
    "icon": "✏️",
    "summary": "Permohonan bantuan memperbaiki elemen data atau anggota KK.",
    "fields": [
      {
        "key": "no_kk",
        "label": "Nomor KK lama",
        "type": "text",
        "required": true
      },
      {
        "key": "kepala",
        "label": "Nama kepala keluarga",
        "type": "text",
        "required": true
      },
      {
        "key": "jenis",
        "label": "Jenis perubahan",
        "type": "select",
        "required": true,
        "options": [
          "Nama",
          "Tempat/tanggal lahir",
          "Status perkawinan",
          "Pendidikan",
          "Pekerjaan",
          "Alamat",
          "Anggota keluarga",
          "Lainnya"
        ]
      },
      {
        "key": "nama_anggota",
        "label": "Nama anggota keluarga terkait",
        "type": "text",
        "required": true
      },
      {
        "key": "nik_anggota",
        "label": "NIK anggota terkait",
        "type": "text",
        "required": true
      },
      {
        "key": "sebelum",
        "label": "Data sebelum perubahan",
        "type": "textarea",
        "required": true
      },
      {
        "key": "sesudah",
        "label": "Data sesudah perubahan",
        "type": "textarea",
        "required": true
      },
      {
        "key": "alasan",
        "label": "Alasan perubahan",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "kk",
        "label": "Foto / scan KK lama",
        "required": true
      },
      {
        "key": "ktp",
        "label": "Foto / scan KTP pemohon",
        "required": true
      },
      {
        "key": "bukti",
        "label": "Foto / scan dokumen bukti perubahan",
        "required": true
      }
    ]
  },
  "Surat Keterangan atau Pengantar Kehilangan Dokumen": {
    "icon": "📄",
    "summary": "Pengantar administrasi desa; bukan surat kehilangan resmi dari kepolisian.",
    "fields": [
      {
        "key": "jenis",
        "label": "Jenis dokumen yang hilang",
        "type": "select",
        "required": true,
        "options": [
          "KTP",
          "KK",
          "Akta kelahiran",
          "Ijazah",
          "Lainnya"
        ]
      },
      {
        "key": "nama_pemilik",
        "label": "Nama pemilik dokumen",
        "type": "text",
        "required": true
      },
      {
        "key": "nomor",
        "label": "Nomor dokumen (jika diketahui)",
        "type": "text",
        "required": false
      },
      {
        "key": "tanggal",
        "label": "Perkiraan tanggal kehilangan",
        "type": "date",
        "required": true
      },
      {
        "key": "lokasi",
        "label": "Lokasi kehilangan",
        "type": "textarea",
        "required": true
      },
      {
        "key": "kronologi",
        "label": "Kronologi kehilangan",
        "type": "textarea",
        "required": true
      },
      {
        "key": "lapor",
        "label": "Sudah melapor ke polisi?",
        "type": "select",
        "required": true,
        "options": [
          "Ya",
          "Tidak"
        ]
      },
      {
        "key": "nomor_laporan",
        "label": "Nomor laporan polisi",
        "type": true,
        "required": true,
        "when": {
          "key": "lapor",
          "in": [
            "Ya"
          ]
        }
      },
      {
        "key": "keperluan",
        "label": "Keperluan surat",
        "type": "textarea",
        "required": true
      }
    ],
    "docs": [
      {
        "key": "salinan",
        "label": "Foto / scan salinan dokumen jika masih ada",
        "required": false
      },
      {
        "key": "laporan",
        "label": "Foto / scan surat kehilangan kepolisian",
        "required": false,
        "when": {
          "key": "lapor",
          "in": [
            "Ya"
          ]
        }
      }
    ]
  }
}


JSON, true);

if (!is_array($layanan)) {
    throw new RuntimeException(
        'Konfigurasi surat tidak valid: ' . json_last_error_msg()
    );
}

$penyesuaian = require __DIR__
    . '/desara_kependudukan_penyesuaian.php';

foreach ($layanan as $namaSurat => &$surat) {

    $surat['nama'] = $namaSurat;

    $tambahan = $penyesuaian[$namaSurat] ?? [];

    foreach (['fields', 'docs'] as $bagian) {

        $data = [];

        // Ambil field atau dokumen lama berdasarkan key unik.
        foreach (($surat[$bagian] ?? []) as $index => $item) {
            $key = $item['key'] ?? 'item_' . $index;
            $data[$key] = $item;
        }

        // Perbarui data lama dan tambahkan data baru.
        foreach (($tambahan[$bagian] ?? []) as $key => $item) {

            $data[$key] = array_replace(
                $data[$key] ?? [],
                $item,
                ['key' => $key]
            );
        }

        // Kembalikan menjadi array yang bisa di-loop Blade.
        $surat[$bagian] = array_values($data);
    }

    // Pastikan semua jenis input valid.
    foreach ($surat['fields'] as &$field) {
        if (!is_string($field['type'] ?? null)) {
            $field['type'] = 'text';
        }
    }

    unset($field);

    // Kompatibilitas dengan kode Blade yang sudah ada.
    $surat['uploads'] = $surat['docs'];
}

unset($surat);

return $layanan;