
@php
    // DATA DARI DATABASE
    $pemohon = $pengajuan->data_pemohon ?? [];
    $data = $pengajuan->data_surat ?? [];

    if (is_string($pemohon)) {
        $pemohon = json_decode($pemohon, true) ?: [];
    }

    if (is_string($data)) {
        $data = json_decode($data, true) ?: [];
    }

    // LOGO KABUPATEN MAGETAN
    $logoPath = public_path(
        'images/logo-magetan-transparan.png'
    );

    // Fallback apabila logo transparan belum dipasang.
    if (!is_file($logoPath)) {
        $logoPath = public_path('images/logo-magetan.png');
    }

    $logoBase64 = null;

    if (is_file($logoPath) && is_readable($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' .
            base64_encode(file_get_contents($logoPath));
    }

    // IDENTITAS SURAT
    $jenisSurat = $pengajuan->jenis_surat
        ?? 'Surat Keterangan';

    $nomorSurat = $pengajuan->nomor_surat ?? null;

    // IDENTITAS PEMOHON
    $nama = $pemohon['nama_pemohon']
        ?? $pemohon['nama_lengkap']
        ?? $pemohon['nama']
        ?? '-';

    $nik = $pemohon['nik'] ?? '-';

    $tempatLahir = $pemohon['tempat_lahir'] ?? '';
    $tanggalLahir = $pemohon['tanggal_lahir'] ?? null;

    $tanggalLahirTeks = '';

    if (filled($tanggalLahir)) {
        try {
            $tanggalLahirTeks =
                \Illuminate\Support\Carbon::parse(
                    $tanggalLahir
                )
                ->locale('id')
                ->translatedFormat('d F Y');
        } catch (\Throwable $e) {
            $tanggalLahirTeks = (string) $tanggalLahir;
        }
    }

    $ttl = collect([
        $tempatLahir,
        $tanggalLahirTeks,
    ])
        ->filter(fn ($nilai) => filled($nilai))
        ->implode(', ');

    $alamat = $pemohon['alamat'] ?? '-';
    $jenisKelamin = $pemohon['jenis_kelamin'] ?? '-';
    $pekerjaan = $pemohon['pekerjaan'] ?? '-';
    $statusPerkawinan =
        $pemohon['status_perkawinan'] ?? '-';

    // DATA KETERANGAN SURAT
    $keperluan = $data['keperluan']
        ?? $data['alasan']
        ?? null;

    $keterangan = $data['keterangan']
        ?? $data['kronologi']
        ?? null;

    $tanggalSurat = now()
        ->locale('id')
        ->translatedFormat('d F Y');

    // Belum ada identitas pejabat penandatangan.
    $namaKepalaDesa = null;

    // LABEL DINAMIS BERDASARKAN JENIS SURAT
    $konfigurasi = config('desara_kependudukan', []);

    $fields = $konfigurasi[$jenisSurat]['fields'] ?? [];

    $labelField = [];

    foreach ($fields as $field) {
        if (isset($field['key'], $field['label'])) {
            $labelField[$field['key']] = $field['label'];
        }
    }

    // DATA TAMBAHAN TANPA DUPLIKASI KEPERLUAN
    $dataTambahan = collect($data)->except([
        'keperluan',
        'alasan',
        'keterangan',
        'kronologi',
    ]);

    // FORMAT NILAI DINAMIS
    $formatNilai = function ($nilai) {
        if ($nilai === null || $nilai === '') {
            return '-';
        }

        if (is_bool($nilai)) {
            return $nilai ? 'Ya' : 'Tidak';
        }

        if (is_array($nilai)) {
            $hasil = [];

            foreach ($nilai as $item) {
                if (is_array($item)) {
                    $baris = array_map(
                        fn ($v) => is_scalar($v)
                            ? (string) $v
                            : '',
                        $item
                    );

                    $hasil[] = implode(', ', $baris);
                } elseif (is_scalar($item)) {
                    $hasil[] = (string) $item;
                }
            }

            return implode('; ', $hasil) ?: '-';
        }

        return (string) $nilai;
    };
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4 portrait;
            margin: 11mm 16mm 11mm 16mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8.5pt;
            line-height: 1.25;
            color: #111;
        }

        /* KOP SURAT */
        .kop {
            width: 100%;
            padding-bottom: 6px;
            margin-bottom: 11px;
            border-bottom: 2.5px solid #111;
            page-break-inside: avoid;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo {
            width: 19%;
            text-align: center;
        }

        .kop-logo img {
            width: 100px;
            height: auto;

            position: relative;
            left: 35px;  /* Geser ke kanan */
            top: 0px;  
        }

        .kop-teks {
            width: 81%;
            text-align: center;
        }

        .kabupaten {
            font-size: 14pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .kecamatan {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .desa {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .alamat-kantor {
            margin-top: 5px;
            font-size: 7pt;
            text-decoration: underline;
        }

        /* JUDUL SURAT */
        .judul {
            margin: 9px 0 12px;
            text-align: center;
            page-break-inside: avoid;
        }

        .judul h2 {
            margin: 0;
            padding: 0;
            font-size: 10pt;
            line-height: 1.3;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .judul .nomor {
            margin-top: 3px;
            font-size: 8.5pt;
        }

        /* PARAGRAF */
        .pembuka {
            margin: 0 0 7px;
            text-align: justify;
        }

        .penutup {
            margin: 10px 0 0;
            text-align: justify;
        }

        /* TABEL IDENTITAS DAN DATA SURAT */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 0 8px;
        }

        .data-table td {
            padding: 2px 0;
            border: none;
            vertical-align: top;
            font-size: 8.5pt;
            line-height: 1.25;
        }

        .data-table tr {
            page-break-inside: avoid;
        }

        .label-col {
            width: 40%;
            padding-right: 8px;
            word-wrap: break-word;
        }

        .colon-col {
            width: 4%;
            text-align: left;
        }

        .value-col {
            width: 56%;
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }

        /* KETERANGAN TAMBAHAN */
        .bagian-tambahan {
            margin: 7px 0 8px;
        }

        .bagian-tambahan h3 {
            margin: 0 0 5px;
            font-size: 8.5pt;
            font-weight: bold;
        }

        /* TANDA TANGAN */
        .bagian-akhir {
            page-break-inside: avoid;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 11px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .ttd-kosong {
            width: 52%;
        }

        .ttd-isi {
            width: 48%;
            text-align: center;
            font-size: 8.5pt;
            line-height: 1.35;
        }

        .ruang-ttd {
            height: 34px;
        }

        .nama-kades {
            font-weight: bold;
            text-decoration: underline;
        }

        /* CATATAN DRAFT */
        .catatan-draft {
            margin-top: 9px;
            padding-top: 4px;
            border-top: 1px dashed #aaa;
            text-align: center;
            color: #777;
            font-size: 6.5pt;
        }
    </style>
</head>

<body>

    {{-- KOP SURAT --}}
    <div class="kop">
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img
                            src="{{ $logoBase64 }}"
                            alt="Logo Kabupaten Magetan"
                        >
                    @endif
                </td>

                <td class="kop-teks">
                    <div class="kabupaten">
                        PEMERINTAH KABUPATEN MAGETAN
                    </div>

                    <div class="kecamatan">
                        KECAMATAN PANEKAN
                    </div>

                    <div class="desa">
                        DESA MILANGASRI
                    </div>

                    <div class="alamat-kantor">
                        Kantor: Jl. Mayjend. Sukowati No. 35,
                        Telp. (0351) 892826
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- JUDUL DAN NOMOR SURAT --}}
    <div class="judul">
        <h2>{{ $jenisSurat }}</h2>

        <div class="nomor">
            Nomor :
            {{ filled($nomorSurat)
                ? $nomorSurat
                : '...................................' }}
        </div>
    </div>

    {{-- PEMBUKA --}}
    <p class="pembuka">
        Yang bertanda tangan di bawah ini menerangkan bahwa:
    </p>

    {{-- IDENTITAS PEMOHON --}}
    <table class="data-table">

        <tr>
            <td class="label-col">Nama</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $nama }}</td>
        </tr>

        <tr>
            <td class="label-col">NIK</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $nik }}</td>
        </tr>

        <tr>
            <td class="label-col">Tempat, Tanggal Lahir</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $ttl ?: '-' }}</td>
        </tr>

        <tr>
            <td class="label-col">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $jenisKelamin }}</td>
        </tr>

        <tr>
            <td class="label-col">Pekerjaan</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $pekerjaan }}</td>
        </tr>

        <tr>
            <td class="label-col">Status Perkawinan</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $statusPerkawinan }}</td>
        </tr>

        <tr>
            <td class="label-col">Alamat</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $alamat }}</td>
        </tr>

        @if(filled($keterangan))
            <tr>
                <td class="label-col">Keterangan</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $keterangan }}</td>
            </tr>
        @endif

        {{-- KEPERLUAN SEKARANG SEJAJAR --}}
        @if(filled($keperluan))
            <tr>
                <td class="label-col">Keperluan</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $keperluan }}</td>
            </tr>
        @endif

    </table>

    {{-- DATA TAMBAHAN SESUAI JENIS SURAT --}}
    @if($dataTambahan->isNotEmpty())

        <div class="bagian-tambahan">
            <h3>Data Keterangan Tambahan</h3>

            <table class="data-table">
                @foreach($dataTambahan as $key => $nilai)
                    <tr>
                        <td class="label-col">
                            {{ $labelField[$key]
                                ?? ucwords(str_replace('_', ' ', $key)) }}
                        </td>

                        <td class="colon-col">:</td>

                        <td class="value-col">
                            {{ $formatNilai($nilai) }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    @endif

    {{-- PENUTUP DAN TANDA TANGAN --}}
    <div class="bagian-akhir">

        <p class="penutup">
            Demikian surat keterangan ini dibuat
            untuk digunakan sebagaimana mestinya.
        </p>

        <table class="ttd-table">
            <tr>
                <td class="ttd-kosong"></td>

                <td class="ttd-isi">
                    <div>
                        Milangasri, {{ $tanggalSurat }}
                    </div>

                    <div>
                        Kepala Desa Milangasri
                    </div>

                    <div class="ruang-ttd"></div>

                    <div class="nama-kades">
                        {{ $namaKepalaDesa ?: '(Nama Kepala Desa)' }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="catatan-draft">
            DRAFT PEMERIKSAAN DESARA —
            BELUM MERUPAKAN SURAT RESMI
        </div>

    </div>

</body>
</html>