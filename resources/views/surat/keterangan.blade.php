<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 30px 50px;
        }
        .kop {
            display: table;
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-logo {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
        }
        .kop-logo img {
            width: 75px;
        }
        .kop-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }
        .kop-text h1 {
            font-size: 15px;
            margin: 0;
            font-weight: bold;
        }
        .kop-text h2 {
            font-size: 15px;
            margin: 0;
            font-weight: bold;
        }
        .kop-text h3 {
            font-size: 18px;
            margin: 2px 0;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 11px;
            margin: 2px 0 0 0;
            font-style: italic;
        }
        .judul {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul h4 {
            font-size: 14px;
            text-decoration: underline;
            margin: 0;
        }
        .judul p {
            font-size: 12px;
            margin: 2px 0 0 0;
        }
        .isi p {
            text-align: justify;
            margin: 0 0 10px 0;
        }
        table.data {
            margin: 10px 0 15px 0;
        }
        table.data td {
            padding: 2px 0;
            vertical-align: top;
        }
        table.data td.label {
            width: 190px;
        }
        table.data td.titik {
            width: 15px;
        }
        .ttd {
            margin-top: 40px;
            text-align: right;
        }
        .ttd .box {
            display: inline-block;
            text-align: center;
            width: 260px;
        }
        .ttd .nama {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 60px;
        }
    </style>
</head>
<body>

    <div class="kop">
        <div class="kop-logo">
            <img src="{{ public_path('images/logo-magetan.png') }}">
        </div>
        <div class="kop-text">
            <h1>PEMERINTAH KABUPATEN MAGETAN</h1>
            <h2>KECAMATAN PANEKAN</h2>
            <h3>DESA MILANGASRI</h3>
            <p>Kantor : Jl. Mayjend. Sukowati No. 35, Telp. (0351) 892826</p>
        </div>
    </div>

    <div class="judul">
        <h4>SURAT KETERANGAN</h4>
        <p>Nomor : {{ $nomor_surat }}</p>
    </div>

    <div class="isi">
        <p>Yang bertanda tangan di bawah ini Kepala Desa Milangasri Kecamatan Panekan Kabupaten Magetan, dengan ini menerangkan dengan sesungguhnya bahwa :</p>

        <table class="data" width="100%">
            <tr><td class="label">Nama</td><td class="titik">:</td><td><b>{{ $nama_pemohon }}</b></td></tr>
            <tr><td class="label">NIK</td><td class="titik">:</td><td>{{ $nik_pemohon }}</td></tr>
            <tr><td class="label">Tempat, Tanggal Lahir</td><td class="titik">:</td><td>{{ $tempat_lahir }}, {{ $tanggal_lahir }}</td></tr>
            <tr><td class="label">Pekerjaan</td><td class="titik">:</td><td>{{ $pekerjaan }}</td></tr>
            <tr><td class="label">Agama</td><td class="titik">:</td><td>{{ $agama }}</td></tr>
            <tr><td class="label">Status Perkawinan</td><td class="titik">:</td><td>{{ $status_perkawinan }}</td></tr>
            <tr><td class="label">Jenis Kelamin</td><td class="titik">:</td><td>{{ $jenis_kelamin }}</td></tr>
            <tr><td class="label">Alamat</td><td class="titik">:</td><td>{{ $alamat_pemohon }}</td></tr>
            <tr><td class="label">Keterangan</td><td class="titik">:</td><td>{{ $keterangan }}</td></tr>
        </table>

        <p><b>Keperluan</b> : {{ $keperluan }}</p>
        <p><b>Masa berlaku</b> : {{ $masa_mulai }} s/d {{ $masa_selesai }}</p>

        <p>Demikian surat keterangan ini kami buat untuk digunakan seperlunya.</p>
    </div>

    <div class="ttd">
        <div class="box">
            <p>Milangasri, {{ $tanggal_terbit }}<br>Kepala Desa Milangasri</p>
            <p class="nama">ANGGIT ARDIYANTO, SH</p>
        </div>
    </div>

</body>
</html>