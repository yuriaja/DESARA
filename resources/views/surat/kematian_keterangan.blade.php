<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 17mm 22mm 14mm 21mm; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11pt; line-height: 1.32; color: #1c1c1c; margin: 0; }

        table { border-collapse: collapse; }
        td { padding: 0; vertical-align: top; }

        /* Kop surat */
        .kop { width: 100%; }
        .kop .logo { width: 22mm; vertical-align: middle; }
        .kop .logo img { width: 22mm; }
        .kop .teks { text-align: center; vertical-align: middle; font-weight: bold; font-size: 12.5pt; line-height: 1.2; padding-right: 8mm; }
        .kop .teks .besar { font-size: 15pt; }
        .kop .teks .kantor { font-weight: normal; font-style: italic; font-size: 9.5pt; margin-top: 1mm; }
        .garis { height: 0; border-top: 1mm solid #1c1c1c; border-bottom: 0.3mm solid #1c1c1c; padding-top: 0.6mm; margin: 2mm 0 7mm 0; }

        /* Judul */
        .judul { text-align: center; font-weight: bold; margin: 6mm 0 5mm 0; line-height: 1.3; }
        .judul .u { text-decoration: underline; }
        .judul .no { font-weight: normal; }

        .lead { margin: 3mm 0 2.5mm 0; }

        /* Baris data */
        table.data { margin-left: 15mm; margin-bottom: 3mm; width: 100%; }
        table.data td.label { width: 49mm; }
        table.data td.nilai { padding-left: 1mm; }
        .tebal { font-weight: bold; }

        .penutup { margin-top: 6mm; margin-bottom: 2mm; text-align: justify; }

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
                <div>KECAMATAN PANEKAN</div>
                <div class="besar">DESA MILANGASRI</div>
                <div class="kantor">Kantor : Jl. Mayjend. Sukowati No. 35, Telp. (0351) 892826</div>
            </td>
        </tr>
    </table>
    <div class="garis"></div>

    <div class="judul">
        <span class="u">SURAT KETERANGAN KEMATIAN</span><br>
        <span class="no">Nomor : {{ $nomor_tampil }}</span>
    </div>

    <p class="lead">Yang bertanda tangan di bawah ini :</p>
    <table class="data">
        <tr><td class="label">Nama</td><td class="nilai tebal">: {{ $pemberi_nama }}</td></tr>
        <tr><td class="label">Alamat</td><td class="nilai">: {{ $pemberi_alamat }}</td></tr>
        <tr><td class="label">Jabatan</td><td class="nilai">: {{ $pemberi_jabatan }}</td></tr>
    </table>

    <p class="lead">Dengan ini menerangkan bahwa warga kami :</p>
    <table class="data">
        <tr><td class="label">Nama</td><td class="nilai tebal">: {{ $nama }}</td></tr>
        <tr><td class="label">Nomor NIK</td><td class="nilai">: {{ $nik ?: '-' }}</td></tr>
        <tr><td class="label">Umur</td><td class="nilai">: {{ $umur }}</td></tr>
        <tr><td class="label">Agama</td><td class="nilai">: {{ $agama }}</td></tr>
        <tr><td class="label">Alamat</td><td class="nilai">: {{ $alamat }}</td></tr>
    </table>

    <p class="lead">Telah Meninggal Dunia pada :</p>
    <table class="data">
        <tr><td class="label">Hari</td><td class="nilai">: {{ $hari }}</td></tr>
        <tr><td class="label">Tanggal</td><td class="nilai">: {{ $tanggal_meninggal }}</td></tr>
        <tr><td class="label">Pukul</td><td class="nilai">: {{ $pukul }}</td></tr>
        <tr><td class="label">Disebabkan Karena</td><td class="nilai">: {{ $sebab }}</td></tr>
        <tr><td class="label">Tempat</td><td class="nilai">: {{ $tempat }}</td></tr>
    </table>

    <p class="penutup">Demikian surat keterangan ini di buat dengan sebenar- benarnya , untuk dapat dipergunakan sebagaimana mestinya.</p>

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