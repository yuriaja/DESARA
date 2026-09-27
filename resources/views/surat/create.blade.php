<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Surat Keterangan</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 20px; padding: 10px 20px; background: #1f1c18; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Buat Surat Keterangan</h2>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/surat/keterangan">
        @csrf
        <label>Nama Pemohon</label>
        <input type="text" name="nama_pemohon" required>

        <label>NIK</label>
        <input type="text" name="nik_pemohon" maxlength="16" required>

        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir">

        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir">

        <label>Pekerjaan</label>
        <input type="text" name="pekerjaan">

        <label>Agama</label>
        <input type="text" name="agama">

        <label>Status Perkawinan</label>
        <input type="text" name="status_perkawinan">

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin">
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>

        <label>Alamat</label>
        <textarea name="alamat_pemohon" required></textarea>

        <label>Keterangan</label>
        <textarea name="keterangan"></textarea>

        <label>Keperluan</label>
        <input type="text" name="keperluan">

        <label>Mulai Berlaku</label>
        <input type="date" name="masa_mulai">

        <label>Sampai</label>
        <input type="date" name="masa_selesai">

        <button type="submit">Buat Surat</button>
    </form>
</body>
</html>