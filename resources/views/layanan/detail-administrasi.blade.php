@extends('layanan.layout')

@section('title', 'Detail Pengajuan Administrasi')

@section('content')
@php
    $dataPemohon = is_array($pengajuan->data_pemohon)
        ? $pengajuan->data_pemohon
        : json_decode($pengajuan->data_pemohon ?? '{}', true);

    $dataSurat = is_array($pengajuan->data_surat)
        ? $pengajuan->data_surat
        : json_decode($pengajuan->data_surat ?? '{}', true);

    $dokumen = is_array($pengajuan->dokumen)
        ? $pengajuan->dokumen
        : json_decode($pengajuan->dokumen ?? '{}', true);

    $labelPemohon = [
        'nama' => 'Nama',
        'nik' => 'NIK',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'tempat_tanggal_lahir' => 'Tempat, Tanggal Lahir',
        'jenis_kelamin' => 'Jenis Kelamin',
        'pekerjaan' => 'Pekerjaan',
        'status_perkawinan' => 'Status Perkawinan',
        'alamat' => 'Alamat',
        'telepon' => 'Nomor HP',
        'nomor_hp' => 'Nomor HP',
        'email' => 'Email',
        'no_kk' => 'Nomor KK',
        'kk' => 'Nomor KK',
        'keperluan' => 'Keperluan',
    ];

    $labelSurat = [
        'jenis' => 'Jenis Perubahan',
        'jenis_perubahan' => 'Jenis Perubahan',
        'no_kk' => 'Nomor KK Lama',
        'nomor_kk_lama' => 'Nomor KK Lama',
        'nama_kepala_keluarga' => 'Nama Kepala Keluarga',
        'data_sebelum' => 'Data Sebelum Perubahan',
        'data_sesudah' => 'Data Sesudah Perubahan',
        'nik_anggota_terkait' => 'NIK Anggota Terkait',
        'nama_anggota_keluarga_terkait' => 'Nama Anggota Keluarga Terkait',
        'dokumen_dasar' => 'Dokumen yang Menjadi Dasar Perubahan',
        'tanggal_peristiwa' => 'Tanggal Peristiwa / Perubahan',
        'alasan' => 'Alasan',
        'tujuan_pindah' => 'Tujuan Pindah',
        'alamat_tujuan' => 'Alamat Tujuan',
        'nama_dokumen_hilang' => 'Nama Dokumen Hilang',
        'nomor_dokumen' => 'Nomor Dokumen',
        'tanggal_hilang' => 'Tanggal Hilang',
        'lokasi_hilang' => 'Lokasi Hilang',
        'hubungan_keluarga' => 'Hubungan Keluarga',
    ];

    function tampilLabel($key, $map = []) {
        return $map[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    function tampilNilai($value) {
        if (is_array($value)) {
            return implode(', ', $value);
        }

        if ($value === null || $value === '') {
            return '-';
        }

        return $value;
    }
@endphp

<style>
    .detail-wrap {
        max-width: 1000px;
    }

    .detail-header {
        margin-bottom: 20px;
    }

    .detail-header h1 {
        margin-bottom: 8px;
    }

    .detail-box {
        background: #fff;
        border: 1px solid #dce7e0;
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 18px;
    }

    .detail-box h2 {
        margin: 0 0 14px;
        font-size: 22px;
        color: #14392c;
    }

    .detail-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px 20px;
        margin-bottom: 8px;
    }

    .meta-item {
        background: #f8fbf9;
        border: 1px solid #e1ebe5;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .meta-item small {
        display: block;
        color: #708177;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .meta-item strong {
        font-size: 17px;
        color: #18392e;
    }

    .kv-table {
        width: 100%;
        border-collapse: collapse;
    }

    .kv-table tr td {
        padding: 6px 0;
        vertical-align: top;
        font-size: 15px;
    }

    .kv-label {
        width: 260px;
        color: #1e302a;
        font-weight: 500;
    }

    .kv-colon {
        width: 20px;
        text-align: center;
        color: #1e302a;
        font-weight: 600;
    }

    .kv-value {
        color: #111;
    }

    .dokumen-list {
        display: grid;
        gap: 10px;
    }

    .dokumen-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border: 1px solid #e1ebe5;
        border-radius: 12px;
        background: #f9fbfa;
    }

    .dokumen-item .nama-dokumen {
        font-weight: 600;
        color: #18392e;
    }

    .dokumen-item .path-dokumen {
        font-size: 13px;
        color: #68786f;
        margin-top: 4px;
        word-break: break-word;
    }

    .btn-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .btn {
        display: inline-block;
        background: #23694d;
        color: #fff;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    .btn.secondary {
        background: #edf4ef;
        color: #23694d;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
    }

    .status-antrian {
        background: #f8ecd0;
        color: #8a6500;
    }

    .status-proses {
        background: #dff3e8;
        color: #1d6945;
    }

    .status-selesai {
        background: #dbe9ff;
        color: #2053a3;
    }

    .status-ditolak {
        background: #fde1e1;
        color: #a12828;
    }

    @media (max-width: 768px) {
        .detail-meta {
            grid-template-columns: 1fr;
        }

        .kv-label {
            width: 180px;
        }
    }
</style>

<div class="detail-wrap">
    <div class="detail-header">
        <p class="muted" style="margin-bottom:8px;">
            <a href="{{ route('petugas.rekap') }}">Rekap Administrasi</a> /
            Detail Pengajuan
        </p>
        <h1>Detail Pengajuan</h1>
        <p class="muted">Lihat rincian data warga, data surat, lampiran, dan file PDF surat.</p>
    </div>

    <div class="detail-box">
        <h2>Informasi Pengajuan</h2>

        <div class="detail-meta">
            <div class="meta-item">
                <small>Nomor Pengajuan</small>
                <strong>{{ $pengajuan->nomor_pengajuan ?? '-' }}</strong>
            </div>

            <div class="meta-item">
                <small>Jenis Surat</small>
                <strong>{{ $pengajuan->jenis_surat ?? '-' }}</strong>
            </div>

            <div class="meta-item">
                <small>Tanggal Masuk</small>
                <strong>{{ optional($pengajuan->created_at)->format('d/m/Y H:i') }}</strong>
            </div>

            <div class="meta-item">
                <small>Status</small>
                @php
                    $status = strtolower($pengajuan->status ?? '');
                    $statusClass = match($status) {
                        'dalam antrian' => 'status-antrian',
                        'proses' => 'status-proses',
                        'selesai' => 'status-selesai',
                        'ditolak' => 'status-ditolak',
                        default => 'status-antrian'
                    };
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    {{ ucfirst($pengajuan->status ?? '-') }}
                </span>
            </div>
        </div>
    </div>

    <div class="detail-box">
        <h2>Identitas Pemohon / Orang yang Diterangkan</h2>

        <table class="kv-table">
            @forelse($dataPemohon as $key => $value)
                <tr>
                    <td class="kv-label">{{ tampilLabel($key, $labelPemohon) }}</td>
                    <td class="kv-colon">:</td>
                    <td class="kv-value">{{ tampilNilai($value) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="muted">Data pemohon belum tersedia.</td>
                </tr>
            @endforelse
        </table>
    </div>

    <div class="detail-box">
        <h2>Data Keterangan Tambahan</h2>

        <table class="kv-table">
            @forelse($dataSurat as $key => $value)
                <tr>
                    <td class="kv-label">{{ tampilLabel($key, $labelSurat) }}</td>
                    <td class="kv-colon">:</td>
                    <td class="kv-value">{{ tampilNilai($value) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="muted">Data surat belum tersedia.</td>
                </tr>
            @endforelse
        </table>
    </div>

    <div class="detail-box">
        <h2>Lampiran Dokumen</h2>

        <div class="dokumen-list">
            @forelse($dokumen as $key => $path)
                <div class="dokumen-item">
                    <div>
                        <div class="nama-dokumen">{{ tampilLabel($key) }}</div>
                        <div class="path-dokumen">{{ $path }}</div>
                    </div>
                    
                    <a
                        href="{{ route('petugas.administrasi.lampiran', [
                            'pengajuan' => $pengajuan->id,
                            'kode' => $key
                        ]) }}"
                        target="_blank"
                        rel="noopener"
                        class="btn secondary"
                    >
                        Lihat Dokumen
                    </a>

                </div>
            @empty
                <p class="muted">Tidak ada dokumen terlampir.</p>
            @endforelse
        </div>
    </div>

    <div class="detail-box">
        <h2>PDF Surat</h2>

        <div class="btn-row">
            <a href="{{ route('petugas.administrasi.pdf', $pengajuan->id) }}" target="_blank" class="btn">
                Buka PDF Surat
            </a>

            <a href="{{ route('petugas.rekap') }}" class="btn secondary">
                Kembali ke Rekap
            </a>
        </div>
    </div>
</div>
@endsection