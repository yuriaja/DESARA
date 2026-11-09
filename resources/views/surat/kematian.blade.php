<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 17mm 22mm 14mm 24mm; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11.5pt; line-height: 1.38; color: #1c1c1c; margin: 0; }

        table { border-collapse: collapse; }
        td { padding: 0; vertical-align: top; }

        /* Kop surat */
        .kop { width: 100%; }
        .kop .logo { width: 22mm; vertical-align: middle; }
        .kop .logo img { width: 22mm; }
        .kop .teks { text-align: center; vertical-align: middle; font-weight: bold; font-size: 11.5pt; line-height: 1.4; }
        .kop .grid { margin: 0 auto; }
        .kop .grid td { text-align: left; font-weight: bold; }
        .kop .grid td.k { width: 28mm; }
        .alamat { text-align: center; font-weight: bold; font-size: 11pt; text-decoration: underline; margin-top: 1mm; }

        /* Judul */
        .judul { text-align: center; font-weight: bold; margin: 10mm 0 6mm 0; line-height: 1.3; }
        .judul .garis { text-decoration: underline; }

        .lead { margin: 3mm 0 2.5mm 0; }

        /* Baris data */
        table.data { margin-left: 2mm; margin-bottom: 3mm; width: 100%; }
        table.data td.label { width: 62mm; }
        table.data td.nilai { padding-left: 1mm; }

        .penutup { margin-top: 6mm; margin-bottom: 2mm; }

        /* Tanda tangan */
        table.ttd { width: 100%; margin-top: 7mm; }
        table.ttd td { text-align: center; vertical-align: top; }
        .ttd .nama { margin-top: 15mm; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <table class="kop">
        <tr>
            <td class="logo"><img src="{{ public_path('images/logo-magetan.png') }}"></td>
            <td class="teks">
                <div>PEMERINTAH KABUPATEN MAGETAN</div>
                <table class="grid">
                    <tr><td class="k">KECAMATAN</td><td>&nbsp;:&nbsp;</td><td>PANEKAN</td></tr>
                    <tr><td class="k">D E S A</td><td>&nbsp;:&nbsp;</td><td>MILANGASRI</td></tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="alamat">Jalan Mayjen Sungkono Nomor 35 Telpon No 892826</div>

    <div class="judul">
        {{ $judul_atas }}<br>
        <span class="garis">SURAT KEMATIAN</span><br>
        <span style="font-weight: normal;">Nomor : {{ $nomor_tampil }}</span>
    </div>

    <p class="lead">Yang Bertanda Tangan di bawah ini menerangkan bahwa :</p>

    <table class="data">
        <tr><td class="label">Nama</td><td class="nilai">: {{ $nama }}</td></tr>
        <tr><td class="label">Jenis Kelamin</td><td class="nilai">: {{ $jenis_kelamin }}</td></tr>
        <tr><td class="label">Alamat</td><td class="nilai">: {{ $alamat }}</td></tr>
        <tr><td class="label" style="padding-top: 3mm;">Umur</td><td class="nilai" style="padding-top: 3mm;">: {{ $umur }}</td></tr>
        <tr><td class="label">Telah meninggal dunia pada</td><td class="nilai">: {{ $hari }}</td></tr>
        <tr><td class="label">Jam</td><td class="nilai">: {{ $jam }}</td></tr>
        <tr><td class="label">Tanggal</td><td class="nilai">: {{ $tanggal_meninggal }}</td></tr>
        <tr><td class="label">Di</td><td class="nilai">: {{ $tempat }}</td></tr>
        <tr><td class="label">Disebabkan Karena</td><td class="nilai">: {{ $sebab }}</td></tr>
    </table>

    <p class="penutup">Surat Keterangan ini di buat atas dasar yang sebenarnya.</p>

    <table class="ttd">
        <tr>
            @if ($ttd2)
                <td style="width: 50%;">
                    <div>{{ $ttd2['label'] ?: 'Mengetahui' }}</div>
                    <div>{{ $ttd2['jabatan'] }}</div>
                    <div class="nama">{{ $ttd2['nama'] }}</div>
                </td>
            @else
                <td style="width: 50%;"></td>
            @endif
            <td style="width: 50%;">
                <div>{{ $tempat_tanggal }}</div>
                <div>{{ $jabatan }}</div>
                <div class="nama">{{ $nama_penandatangan }}</div>
            </td>
        </tr>
    </table>

</body>
</html>