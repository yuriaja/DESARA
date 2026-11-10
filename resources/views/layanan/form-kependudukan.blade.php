
@extends('layanan.layout')

@php
    $namaSurat = $layanan['nama'] ?? $layanan['title'] ?? $jenis;
    $fields = $layanan['fields'] ?? [];
    $uploads = $layanan['docs'] ?? $layanan['uploads'] ?? [];

    $user = auth()->user();
    $emailUser = $user?->email ?? '';

    // Mode perbaikan pengajuan ditolak
    $isEdit = isset($pengajuan) && $pengajuan !== null;

    $dataPemohonLama = $isEdit
        ? ($pengajuan->data_pemohon ?? [])
        : [];

    $dataSuratLama = $isEdit
        ? ($pengajuan->data_surat ?? [])
        : [];

    $dokumenLama = $isEdit
        ? ($pengajuan->dokumen ?? [])
        : [];

    // Antisipasi jika model belum menggunakan cast array.
    foreach ([
        'dataPemohonLama',
        'dataSuratLama',
        'dokumenLama'
    ] as $variabel) {
        if (is_string($$variabel)) {
            $$variabel = json_decode($$variabel, true) ?: [];
        }

        if (!is_array($$variabel)) {
            $$variabel = [];
        }
    }

    $nilaiPemohon = function ($key, $default = '') use ($dataPemohonLama) {
        return old(
            $key,
            $dataPemohonLama[$key] ?? $default
        );
    };

    $actionForm = $isEdit
        ? route('warga.kependudukan.update', $pengajuan)
        : route('warga.kependudukan.store', ['jenis' => $jenis]);
@endphp

@section('title', $isEdit ? 'Perbaiki ' . $namaSurat : $namaSurat)

@section('content')

<style>
    .form-kependudukan {
        max-width: 1000px;
        margin: 0 auto;
    }

    .form-kependudukan *,
    .form-kependudukan *::before,
    .form-kependudukan *::after {
        box-sizing: border-box;
    }

    .form-kependudukan h1,
    .form-kependudukan h2 {
        color: #173e30;
    }

    .form-header {
        margin-bottom: 24px;
    }

    .form-header h1 {
        margin-bottom: 9px;
        font-size: 30px;
        line-height: 1.3;
    }

    .form-header p {
        color: #61746a;
        line-height: 1.7;
    }

    .form-kependudukan .form-notice {
        padding: 20px;
        background: #edf6ee;
        border: 1px solid #b8dac5;
        border-radius: 14px;
        margin-bottom: 25px;
        line-height: 1.7;
    }

    .form-kependudukan .form-alert {
        padding: 18px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .form-kependudukan .alert-success {
        background: #edf6ee;
        border: 1px solid #b8dac5;
        color: #206b4c;
    }

    .form-kependudukan .alert-error {
        background: #fff2f2;
        border: 1px solid #e8bbbb;
        color: #982b2b;
    }

    .form-kependudukan .alert-error ul {
        margin-top: 10px;
        padding-left: 23px;
    }

    .form-kependudukan .revisi-notice {
        padding: 20px;
        border-radius: 14px;
        background: #fff8eb;
        border: 1px solid #efd7a8;
        color: #76501c;
        margin-bottom: 22px;
        line-height: 1.7;
    }

    .form-kependudukan .revisi-notice strong {
        display: block;
        font-size: 17px;
        margin-bottom: 8px;
    }

    .form-kependudukan .revisi-notice p {
        margin: 5px 0;
    }

    .form-kependudukan .step-indicator {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 28px;
    }

    .form-kependudukan .step-indicator span {
        padding: 15px 8px;
        background: #e9eee9;
        color: #60746a;
        border-radius: 12px;
        text-align: center;
        font-size: 14px;
    }

    .form-kependudukan .step-indicator span.active {
        background: #206b4c;
        color: white;
        font-weight: 600;
    }

    .form-kependudukan .step-indicator span.completed {
        background: #cce7d5;
        color: #174e37;
    }

    .form-kependudukan .card {
        background: white;
        padding: 30px;
        border: 1px solid #dce7df;
        border-radius: 16px;
        margin-bottom: 22px;
    }

    .form-kependudukan .form-section-title {
        font-size: 22px;
        margin: 0 0 15px;
    }

    .form-kependudukan .form-group {
        margin-bottom: 22px;
    }

    .form-kependudukan .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 9px;
        color: #18392e;
    }

    .form-kependudukan input:not([type="checkbox"]),
    .form-kependudukan select,
    .form-kependudukan textarea {
        display: block;
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #ccd8d1;
        border-radius: 9px;
        background: white;
        color: #18392e;
        font: inherit;
    }

    .form-kependudukan textarea {
        min-height: 105px;
        resize: vertical;
    }

    .form-kependudukan input:focus,
    .form-kependudukan select:focus,
    .form-kependudukan textarea:focus {
        outline: 2px solid #75b99a;
        outline-offset: 1px;
    }

    .form-kependudukan input[readonly] {
        background: #f3f6f3;
        color: #61746a;
    }

    .form-kependudukan .required-mark {
        color: #c43838;
    }

    .form-kependudukan .optional,
    .form-kependudukan .help-text {
        color: #71837a;
        font-size: 13px;
    }

    .form-kependudukan .help-text {
        margin-top: 7px;
        line-height: 1.6;
    }

    .form-kependudukan .upload-box {
        padding: 20px;
        border: 1px dashed #a6c4b0;
        background: #f8fbf8;
        border-radius: 12px;
        margin-bottom: 18px;
    }

    .form-kependudukan .upload-existing {
        padding: 12px 14px;
        margin-bottom: 14px;
        background: #e8f5ed;
        border: 1px solid #b9dec9;
        border-radius: 9px;
        color: #206b4c;
        font-size: 14px;
        line-height: 1.6;
    }

    .form-kependudukan .upload-existing strong {
        display: block;
        margin-bottom: 4px;
    }

    .form-kependudukan .file-preview {
        margin-top: 10px;
        font-size: 13px;
        color: #206b4c;
        overflow-wrap: anywhere;
    }

    .form-kependudukan .repeat-row {
        padding: 18px;
        margin-bottom: 16px;
        background: #f8fbf8;
        border: 1px solid #dce7df;
        border-radius: 12px;
    }

    .form-kependudukan .repeat-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        gap: 12px;
    }

    .form-kependudukan .repeat-field {
        margin-bottom: 14px;
    }

    .form-kependudukan .repeat-field label {
        display: block;
        margin-bottom: 7px;
        font-weight: 500;
    }

    .form-kependudukan .btn-hapus-anggota {
        border: none;
        background: #fff1f1;
        color: #a12828;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
    }

    .form-kependudukan .btn-tambah-anggota {
        margin-top: 10px;
    }

    .form-kependudukan .form-step {
        display: none;
    }

    .form-kependudukan .form-step.active {
        display: block;
    }

    .form-kependudukan .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-top: 22px;
    }

    .form-kependudukan .btn-form {
        padding: 13px 22px;
        border: none;
        border-radius: 10px;
        background: #206b4c;
        color: white;
        font: inherit;
        font-weight: 600;
        cursor: pointer;
    }

    .form-kependudukan .btn-form:hover {
        background: #174e37;
    }

    .form-kependudukan .btn-form:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .form-kependudukan .btn-back {
        background: #687a70;
    }

    .form-kependudukan .summary {
        background: #f8faf8;
        padding: 20px;
        border-radius: 12px;
        margin-top: 15px;
    }

    .form-kependudukan .summary-item {
        padding: 12px 0;
        border-bottom: 1px solid #e1e9e3;
        overflow-wrap: anywhere;
    }

    .form-kependudukan .summary-item:last-child {
        border-bottom: none;
    }

    .form-kependudukan .summary-item strong {
        display: block;
        margin-bottom: 6px;
    }

    .form-kependudukan .checkbox-row {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 20px;
        margin-top: 24px;
        background: #f8fbf8;
        border: 1px solid #dce7df;
        border-radius: 12px;
        cursor: pointer;
    }

    .form-kependudukan .checkbox-row input[type="checkbox"] {
        appearance: auto;
        -webkit-appearance: checkbox;
        width: 20px !important;
        height: 20px !important;
        min-width: 20px;
        flex: 0 0 20px;
        margin: 2px 0 0;
        padding: 0;
        accent-color: #206b4c;
        cursor: pointer;
    }

    .form-kependudukan .checkbox-content {
        flex: 1;
        min-width: 0;
        color: #18392e;
        line-height: 1.6;
    }

    .form-kependudukan .checkbox-content strong {
        display: block;
        margin-bottom: 5px;
    }

    .form-kependudukan .checkbox-content p {
        margin: 0;
        font-size: 14px;
    }

    .desara-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 35, 26, 0.55);
    }

    .desara-modal.show {
        display: flex;
    }

    .desara-modal-content {
        width: 100%;
        max-width: 470px;
        padding: 28px;
        border-radius: 18px;
        background: white;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.18);
    }

    .desara-modal-icon {
        font-size: 36px;
        margin-bottom: 12px;
    }

    .desara-modal-content h2 {
        margin: 0 0 12px;
        color: #173e30;
    }

    .desara-modal-content p {
        color: #61746a;
        line-height: 1.7;
    }

    .desara-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
    }

    .desara-modal-actions button {
        padding: 12px 18px;
        border: none;
        border-radius: 9px;
        cursor: pointer;
        font: inherit;
        font-weight: 600;
    }

    .desara-modal-cancel {
        background: #e9eee9;
        color: #29483b;
    }

    .desara-modal-confirm {
        background: #206b4c;
        color: white;
    }

    @media (max-width: 700px) {
        .form-kependudukan .step-indicator {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-kependudukan .card {
            padding: 20px;
        }

        .form-kependudukan .btn-form {
            padding: 12px 15px;
        }

        .desara-modal-actions {
            flex-direction: column-reverse;
        }

        .form-header h1 {
            font-size: 25px;
        }
    }
</style>

<div class="form-kependudukan">

    {{-- BREADCRUMB --}}
    <p class="muted">
        <a href="{{ route('warga.home') }}">
            Pelayanan Administrasi
        </a>
        /
        <a href="{{ url('/warga/kategori/1') }}">
            Administrasi Kependudukan
        </a>
        /
        {{ $namaSurat }}
    </p>

    {{-- HEADER --}}
    <div class="form-header">
        <h1>
            {{ $isEdit ? 'Perbaiki Pengajuan' : $namaSurat }}
        </h1>

        <p>
            @if($isEdit)
                Perbaiki data yang belum sesuai agar pengajuan
                dapat diperiksa kembali oleh petugas desa.
            @else
                Lengkapi formulir dan unggah dokumen
                sesuai persyaratan surat.
            @endif
        </p>
    </div>

    {{-- INFORMASI PENOLAKAN --}}
    @if($isEdit)
        <div class="revisi-notice">
            <strong>Pengajuan Perlu Diperbaiki</strong>

            <p>
                <b>Nomor Pengajuan:</b>
                PGJ-{{ str_pad($pengajuan->id, 5, '0', STR_PAD_LEFT) }}
            </p>

            <p>
                <b>Jenis Surat:</b> {{ $namaSurat }}
            </p>

            <p>
                <b>Alasan Penolakan:</b>
                {{ $pengajuan->catatan_petugas ?: 'Silakan periksa kembali data pengajuan.' }}
            </p>

            <p>
                Seluruh jawaban sebelumnya sudah dimuat otomatis.
                Perbaiki bagian yang salah dan kirim ulang.
                Dokumen yang tidak diganti akan tetap tersimpan.
            </p>
        </div>
    @endif

    {{-- NOTIFIKASI BERHASIL --}}
    @if(session('success'))
        <div class="form-alert alert-success" role="status">
            <strong>Pengajuan berhasil!</strong>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- NOTIFIKASI GAGAL --}}
    @if($errors->any())
        <div class="form-alert alert-error" role="alert">
            <strong>
                {{ $isEdit
                    ? 'Perbaikan belum berhasil disimpan.'
                    : 'Pengajuan belum berhasil dikirim.' }}
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <p>Periksa kembali data yang diisi.</p>
        </div>
    @endif

    {{-- INFORMASI FORM --}}
    <div class="form-notice">
        <strong>
            {{ $isEdit
                ? 'Formulir Perbaikan Administrasi'
                : 'Formulir Pelayanan Administrasi' }}
        </strong>

        <p>
            {{ $layanan['summary'] ?? 'Lengkapi data pengajuan dengan benar.' }}
        </p>

        <p>
            Tanda <span class="required-mark">*</span>
            menunjukkan data wajib diisi.
        </p>
    </div>

    {{-- INDIKATOR TAHAP --}}
    <div class="step-indicator" id="stepIndicator">
        <span class="active">1. Data Pemohon</span>
        <span>2. Data Surat</span>
        <span>3. Upload Dokumen</span>
        <span>4. Konfirmasi</span>
    </div>

    {{-- FORM --}}
    <form
        id="formSurat"
        method="POST"
        action="{{ $actionForm }}"
        enctype="multipart/form-data"
    >
        @csrf

        @if($isEdit)
            @method('PUT')
        @endif

        {{-- TAHAP 1: DATA PEMOHON --}}
        <div class="form-step active" data-step="0">
            <section class="card">
                <h2 class="form-section-title">
                    1. Data Pemohon
                </h2>

                <p class="muted">
                    Isi identitas warga yang mengajukan surat.
                </p>

                <div class="form-group">
                    <label class="form-label" for="nama_pemohon">
                        Nama Lengkap
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        id="nama_pemohon"
                        name="nama_pemohon"
                        type="text"
                        value="{{ $nilaiPemohon('nama_pemohon') }}"
                        placeholder="Masukkan nama sesuai dokumen"
                        maxlength="255"
                        required
                    >

                    <p class="help-text">
                        Bisa untuk diri sendiri atau anggota keluarga.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email_pemohon">
                        Email
                    </label>

                    <input
                        id="email_pemohon"
                        name="email_pemohon"
                        type="email"
                        value="{{ $emailUser }}"
                        readonly
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="nik">
                        NIK
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        id="nik"
                        name="nik"
                        type="text"
                        inputmode="numeric"
                        maxlength="16"
                        pattern="[0-9]{16}"
                        value="{{ $nilaiPemohon('nik', $user?->nik ?? '') }}"
                        placeholder="Masukkan 16 digit NIK"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="nomor_kk">
                        Nomor Kartu Keluarga
                        <span class="optional">(Jika ada)</span>
                    </label>

                    <input
                        id="nomor_kk"
                        name="nomor_kk"
                        type="text"
                        inputmode="numeric"
                        maxlength="16"
                        pattern="[0-9]{16}"
                        value="{{ $nilaiPemohon('nomor_kk') }}"
                        placeholder="Masukkan nomor KK"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="tempat_lahir">
                        Tempat Lahir
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        id="tempat_lahir"
                        name="tempat_lahir"
                        type="text"
                        value="{{ $nilaiPemohon('tempat_lahir') }}"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal_lahir">
                        Tanggal Lahir
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        id="tanggal_lahir"
                        name="tanggal_lahir"
                        type="date"
                        value="{{ $nilaiPemohon('tanggal_lahir') }}"
                        max="{{ date('Y-m-d') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="jenis_kelamin">
                        Jenis Kelamin
                        <span class="required-mark">*</span>
                    </label>

                    <select
                        id="jenis_kelamin"
                        name="jenis_kelamin"
                        required
                    >
                        <option value="">-- Pilih --</option>

                        @foreach(['Laki-laki', 'Perempuan'] as $kelamin)
                            <option
                                value="{{ $kelamin }}"
                                @selected($nilaiPemohon('jenis_kelamin') === $kelamin)
                            >
                                {{ $kelamin }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat">
                        Alamat Sesuai KK
                        <span class="required-mark">*</span>
                    </label>

                    <textarea
                        id="alamat"
                        name="alamat"
                        placeholder="Alamat lengkap sesuai KK"
                        maxlength="2000"
                        required
                    >{{ $nilaiPemohon('alamat', $user?->alamat ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="rt_pemohon">
                        RT <span class="required-mark">*</span>
                    </label>

                    <input
                        id="rt_pemohon"
                        name="rt_pemohon"
                        type="text"
                        maxlength="10"
                        value="{{ $nilaiPemohon('rt_pemohon') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="rw_pemohon">
                        RW <span class="required-mark">*</span>
                    </label>

                    <input
                        id="rw_pemohon"
                        name="rw_pemohon"
                        type="text"
                        maxlength="10"
                        value="{{ $nilaiPemohon('rw_pemohon') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="dusun_pemohon">
                        Dusun <span class="required-mark">*</span>
                    </label>

                    <input
                        id="dusun_pemohon"
                        name="dusun_pemohon"
                        type="text"
                        maxlength="255"
                        value="{{ $nilaiPemohon('dusun_pemohon') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="pekerjaan">
                        Pekerjaan
                        <span class="optional">(Jika diperlukan)</span>
                    </label>

                    <input
                        id="pekerjaan"
                        name="pekerjaan"
                        type="text"
                        maxlength="255"
                        value="{{ $nilaiPemohon('pekerjaan') }}"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="status_perkawinan">
                        Status Perkawinan
                        <span class="optional">(Jika diperlukan)</span>
                    </label>

                    <select
                        id="status_perkawinan"
                        name="status_perkawinan"
                    >
                        <option value="">-- Pilih --</option>

                        @foreach([
                            'Belum kawin',
                            'Kawin',
                            'Cerai hidup',
                            'Cerai mati'
                        ] as $status)
                            <option
                                value="{{ $status }}"
                                @selected($nilaiPemohon('status_perkawinan') === $status)
                            >
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nomor_hp">
                        Nomor HP Aktif
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        id="nomor_hp"
                        name="nomor_hp"
                        type="tel"
                        value="{{ $nilaiPemohon('nomor_hp', $user?->telepon ?? '') }}"
                        placeholder="08xxxxxxxxxx"
                        pattern="08[0-9]{8,13}"
                        required
                    >
                </div>
            </section>
        </div>

        {{-- TAHAP 2: DATA KHUSUS SURAT --}}
        <div class="form-step" data-step="1">
            <section class="card">
                <h2 class="form-section-title">
                    2. Data Khusus Surat
                </h2>

                <p class="muted">
                    Pertanyaan berikut disesuaikan
                    dengan jenis surat yang dipilih.
                </p>

                @forelse($fields as $index => $field)
                    @php
                        $fieldKey = $field['key'] ?? 'field_' . $index;
                        $label = $field['label'] ?? $fieldKey;

                        $tipe = $field['type'] ?? 'text';
                        $tipe = is_string($tipe) ? $tipe : 'text';

                        $wajib = (bool) ($field['required'] ?? false);
                        $pilihan = $field['options'] ?? [];
                        $kondisi = $field['when'] ?? null;
                        $columns = $field['columns'] ?? [];

                        $inputId = 'field_' . $index;

                        $nilaiLama = old(
                            'data.' . $fieldKey,
                            $dataSuratLama[$fieldKey] ?? null
                        );
                    @endphp

                    <div
                        class="form-group conditional-item"
                        data-columns='@json($columns)'
                        @if($kondisi)
                            data-when-key="{{ $kondisi['key'] ?? '' }}"
                            data-when-values='@json($kondisi['in'] ?? [])'
                        @endif
                    >
                        <label
                            class="form-label"
                            for="{{ $inputId }}"
                        >
                            {{ $label }}

                            @if($wajib)
                                <span class="required-mark">*</span>
                            @else
                                <span class="optional">(Opsional)</span>
                            @endif
                        </label>

                        @if($tipe === 'repeat')

                            <div
                                class="repeat-container"
                                data-base-name="data[{{ $fieldKey }}]"
                                data-required="{{ $wajib ? '1' : '0' }}"
                                data-old-rows='@json(is_array($nilaiLama) ? $nilaiLama : [])'
                            >
                                <div class="repeat-list"></div>

                                <button
                                    type="button"
                                    class="btn-form btn-tambah-anggota"
                                >
                                    + Tambah Anggota Keluarga
                                </button>
                            </div>

                        @elseif($tipe === 'textarea')

                            <textarea
                                id="{{ $inputId }}"
                                name="data[{{ $fieldKey }}]"
                                data-field-key="{{ $fieldKey }}"
                                data-required="{{ $wajib ? '1' : '0' }}"
                                maxlength="5000"
                                placeholder="Isi {{ strtolower($label) }}"
                            >{{ is_scalar($nilaiLama) ? $nilaiLama : '' }}</textarea>

                        @elseif($tipe === 'select')

                            <select
                                id="{{ $inputId }}"
                                name="data[{{ $fieldKey }}]"
                                data-field-key="{{ $fieldKey }}"
                                data-required="{{ $wajib ? '1' : '0' }}"
                            >
                                <option value="">
                                    -- Pilih jawaban --
                                </option>

                                @foreach($pilihan as $option)
                                    <option
                                        value="{{ $option }}"
                                        @selected($nilaiLama == $option)
                                    >
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>

                        @else

                            <input
                                id="{{ $inputId }}"
                                name="data[{{ $fieldKey }}]"
                                type="{{ in_array($tipe, ['text', 'number', 'date', 'email', 'tel']) ? $tipe : 'text' }}"
                                data-field-key="{{ $fieldKey }}"
                                data-required="{{ $wajib ? '1' : '0' }}"
                                value="{{ is_scalar($nilaiLama) ? $nilaiLama : '' }}"
                                placeholder="{{ $label }}"
                                @if($tipe === 'number') min="0" step="1" @endif
                            >

                        @endif

                        @if(!empty($field['help']))
                            <p class="help-text">
                                {{ $field['help'] }}
                            </p>
                        @endif
                    </div>

                @empty
                    <p class="muted">
                        Pertanyaan khusus surat belum tersedia.
                    </p>
                @endforelse
            </section>
        </div>

        {{-- TAHAP 3: UPLOAD DOKUMEN --}}
        <div class="form-step" data-step="2">
            <section class="card">
                <h2 class="form-section-title">
                    3. Upload Dokumen Persyaratan
                </h2>

                <p class="muted">
                    Unggah foto atau scan dokumen.
                    Format JPG, PNG, atau PDF.
                    Maksimal 5 MB setiap file.
                </p>

                @if($isEdit)
                    <div class="form-notice">
                        <strong>Dokumen Pengajuan Sebelumnya</strong>
                        <p>
                            Dokumen yang sudah tersimpan tidak perlu
                            diunggah ulang. Pilih file baru hanya
                            jika ingin mengganti dokumen tersebut.
                        </p>
                    </div>
                @endif

                @forelse($uploads as $index => $dokumen)
                    @php
                        $kodeDokumen = $dokumen['key']
                            ?? 'dokumen_' . $index;

                        $namaDokumen = $dokumen['label']
                            ?? 'Dokumen Pendukung';

                        $wajibDokumen = (bool) ($dokumen['required'] ?? false);

                        $kondisiDokumen = $dokumen['when'] ?? null;

                        $sudahAdaDokumen = $isEdit
                            && !empty($dokumenLama[$kodeDokumen]);

                        $wajibUploadBaru = $wajibDokumen && !$sudahAdaDokumen;
                    @endphp

                    <div
                        class="upload-box conditional-item"
                        @if($kondisiDokumen)
                            data-when-key="{{ $kondisiDokumen['key'] ?? '' }}"
                            data-when-values='@json($kondisiDokumen['in'] ?? [])'
                        @endif
                    >
                        <label
                            class="form-label"
                            for="dokumen_{{ $index }}"
                        >
                            {{ $namaDokumen }}

                            @if($wajibDokumen)
                                <span class="required-mark">*</span>
                            @else
                                <span class="optional">(Opsional)</span>
                            @endif
                        </label>

                        @if($sudahAdaDokumen)
                            <div
                                class="upload-existing"
                                data-existing-document="1"
                            >
                                <strong>
                                    ✓ Dokumen sudah tersimpan
                                </strong>

                                Dokumen sebelumnya akan digunakan
                                kembali jika tidak diganti.
                            </div>
                        @endif

                        <input
                            id="dokumen_{{ $index }}"
                            type="file"
                            name="dokumen[{{ $kodeDokumen }}]"
                            accept=".jpg,.jpeg,.png,.pdf"
                            data-required="{{ $wajibUploadBaru ? '1' : '0' }}"
                            @if($wajibUploadBaru) required @endif
                        >

                        <p class="help-text">
                            @if($sudahAdaDokumen)
                                Opsional: pilih file baru untuk
                                mengganti dokumen sebelumnya.
                            @else
                                Pastikan dokumen jelas dan dapat dibaca.
                            @endif
                        </p>

                        <div class="file-preview"></div>
                    </div>

                @empty
                    <p class="muted">
                        Belum ada persyaratan dokumen
                        untuk layanan ini.
                    </p>
                @endforelse
            </section>
        </div>

        {{-- TAHAP 4: KONFIRMASI --}}
        <div class="form-step" data-step="3">
            <section class="card">
                <h2 class="form-section-title">
                    4. Konfirmasi Pengajuan
                </h2>

                <p class="muted">
                    Periksa kembali seluruh data
                    sebelum mengirim pengajuan.
                </p>

                @if($isEdit)
                    <div class="form-notice">
                        <strong>
                            Pengiriman Ulang Pengajuan
                        </strong>

                        <p>
                            Nomor pengajuan tidak berubah.
                            Setelah dikirim ulang, status akan
                            kembali menjadi Dalam Antrian.
                        </p>
                    </div>
                @endif

                <div
                    id="ringkasanForm"
                    class="summary"
                ></div>

                <label
                    class="checkbox-row"
                    for="persetujuan"
                >
                    <input
                        type="checkbox"
                        id="persetujuan"
                        name="persetujuan"
                        required
                    >

                    <div class="checkbox-content">
                        <strong>Pernyataan Persetujuan</strong>

                        <p>
                            Saya menyatakan seluruh data
                            dan dokumen yang diberikan
                            sudah benar dan dapat
                            dipertanggungjawabkan.
                        </p>
                    </div>
                </label>
            </section>
        </div>

        {{-- NAVIGASI --}}
        <div class="form-actions">
            <button
                type="button"
                class="btn-form btn-back"
                id="btnSebelumnya"
                style="display:none"
            >
                ← Sebelumnya
            </button>

            <button
                type="button"
                class="btn-form"
                id="btnSelanjutnya"
            >
                Selanjutnya →
            </button>
        </div>
    </form>
</div>

{{-- POPUP KONFIRMASI --}}
<div
    class="desara-modal"
    id="modalKonfirmasi"
    role="dialog"
    aria-modal="true"
    aria-labelledby="judulModal"
    aria-hidden="true"
>
    <div class="desara-modal-content">
        <div class="desara-modal-icon">📋</div>

        <h2 id="judulModal">
            {{ $isEdit
                ? 'Konfirmasi Perbaikan Pengajuan'
                : 'Konfirmasi Pengajuan' }}
        </h2>

        <p>
            @if($isEdit)
                Apakah kamu yakin semua perbaikan
                sudah sesuai dengan permintaan petugas?
            @else
                Apakah kamu yakin seluruh data
                dan dokumen sudah benar?
            @endif
        </p>

        <p>
            Setelah dikirim, pengajuan akan tercatat
            dan menunggu pemeriksaan petugas desa.
        </p>

        <div class="desara-modal-actions">
            <button
                type="button"
                class="desara-modal-cancel"
                id="btnBatalKirim"
            >
                Periksa Kembali
            </button>

            <button
                type="button"
                class="desara-modal-confirm"
                id="btnYakinKirim"
            >
                {{ $isEdit ? 'Ya, Kirim Ulang' : 'Ya, Kirim' }}
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formSurat');

    if (!form) return;

    const isEdit = @json($isEdit);

    const steps = Array.from(
        form.querySelectorAll('.form-step')
    );

    const indikator = Array.from(
        document.querySelectorAll('#stepIndicator span')
    );

    const btnSebelumnya =
        document.getElementById('btnSebelumnya');

    const btnSelanjutnya =
        document.getElementById('btnSelanjutnya');

    const modal =
        document.getElementById('modalKonfirmasi');

    const btnBatalKirim =
        document.getElementById('btnBatalKirim');

    const btnYakinKirim =
        document.getElementById('btnYakinKirim');

    let tahapAktif = 0;
    let sedangMengirim = false;

    // =====================================================
    // 1. MEMBUAT BARIS ANGGOTA KELUARGA
    // =====================================================

    function tambahAnggota(container, dataAwal = {}) {

        const list = container.querySelector('.repeat-list');
        const group = container.closest('.form-group');

        let columns = {};

        try {
            columns = JSON.parse(group.dataset.columns || '{}');
        } catch (error) {
            columns = {};
        }

        const row = document.createElement('div');
        row.className = 'repeat-row';

        const heading = document.createElement('div');
        heading.className = 'repeat-heading';

        const title = document.createElement('strong');
        title.textContent = 'Anggota Keluarga';

        const hapus = document.createElement('button');
        hapus.type = 'button';
        hapus.className = 'btn-hapus-anggota';
        hapus.textContent = 'Hapus';

        heading.append(title, hapus);
        row.appendChild(heading);

        Object.entries(columns).forEach(function ([key, label]) {

            const wrapper = document.createElement('div');
            wrapper.className = 'repeat-field';

            const labelEl = document.createElement('label');
            labelEl.textContent = label;

            const input = document.createElement('input');
            input.type = 'text';

            input.dataset.column = key;
            input.dataset.required = '1';

            input.placeholder = 'Isi ' + label;
            input.value = dataAwal[key] ?? '';

            const opsional = String(label)
                .toLowerCase()
                .includes('jika ada');

            if (opsional) {
                input.dataset.required = '0';
            }

            if (key === 'nik') {
                input.inputMode = 'numeric';
                input.maxLength = 16;
                input.pattern = '[0-9]{16}';
            }

            wrapper.append(labelEl, input);
            row.appendChild(wrapper);
        });

        hapus.addEventListener('click', function () {

            if (list.children.length > 1) {
                row.remove();
                urutkanAnggota(container);
                perbaruiKondisi();
            }
        });

        list.appendChild(row);

        urutkanAnggota(container);
        perbaruiKondisi();
    }

    // =====================================================
    // 2. MENGURUTKAN INPUT ANGGOTA
    // =====================================================

    function urutkanAnggota(container) {

        const rows = container.querySelectorAll('.repeat-row');
        const baseName = container.dataset.baseName;

        rows.forEach(function (row, index) {

            row.querySelector('.repeat-heading strong')
                .textContent = 'Anggota Keluarga ' + (index + 1);

            row.querySelectorAll('input').forEach(function (input) {

                input.name = baseName +
                    '[' + index + '][' +
                    input.dataset.column + ']';
            });
        });
    }

    // =====================================================
    // 3. KONDISI PERTANYAAN DAN DOKUMEN
    // =====================================================

    function perbaruiKondisi() {

        form.querySelectorAll('.conditional-item')
            .forEach(function (item) {

                const whenKey = item.dataset.whenKey;
                let tampil = true;

                if (whenKey) {

                    const sumber = Array.from(
                        form.querySelectorAll('[data-field-key]')
                    ).find(function (input) {
                        return input.dataset.fieldKey === whenKey;
                    });

                    let pilihan = [];

                    try {
                        pilihan = JSON.parse(
                            item.dataset.whenValues || '[]'
                        );
                    } catch (error) {
                        pilihan = [];
                    }

                    tampil = !!sumber
                        && !sumber.disabled
                        && pilihan.includes(sumber.value);
                }

                item.hidden = !tampil;
                item.style.display = tampil ? '' : 'none';

                item.querySelectorAll(
                    'input, select, textarea, button'
                ).forEach(function (input) {

                    input.disabled = !tampil;

                    if (
                        input.matches(
                            'input:not([type="button"]), select, textarea'
                        )
                    ) {
                        input.required = tampil
                            && input.dataset.required === '1';
                    }
                });
            });
    }

    // =====================================================
    // 4. TAMPILKAN TAHAP
    // =====================================================

    function tampilkanTahap() {

        steps.forEach(function (step, index) {

            step.classList.toggle(
                'active',
                index === tahapAktif
            );
        });

        indikator.forEach(function (item, index) {

            item.classList.toggle(
                'active',
                index === tahapAktif
            );

            item.classList.toggle(
                'completed',
                index < tahapAktif
            );
        });

        btnSebelumnya.style.display =
            tahapAktif === 0 ? 'none' : 'inline-block';

        btnSelanjutnya.textContent =
            tahapAktif === steps.length - 1
                ? (isEdit ? 'Kirim Ulang Pengajuan →' : 'Kirim Pengajuan →')
                : 'Selanjutnya →';

        if (tahapAktif === steps.length - 1) {
            buatRingkasan();
        }
    }

    // =====================================================
    // 5. VALIDASI TAHAP
    // =====================================================

    function validasiTahap(index) {

        perbaruiKondisi();

        const bagian = steps[index];

        const inputs = bagian.querySelectorAll(
            'input, select, textarea'
        );

        for (const input of inputs) {

            if (input.disabled) {
                continue;
            }

            if (input.type === 'file' && input.files.length) {

                const file = input.files[0];

                const ekstensi = file.name
                    .split('.')
                    .pop()
                    .toLowerCase();

                const valid = [
                    'jpg', 'jpeg', 'png', 'pdf'
                ].includes(ekstensi);

                if (!valid || file.size > 5 * 1024 * 1024) {

                    alert(
                        'Dokumen harus JPG, PNG, atau PDF ' +
                        'dengan ukuran maksimal 5 MB.'
                    );

                    tahapAktif = index;
                    tampilkanTahap();

                    input.focus();
                    return false;
                }
            }

            if (!input.checkValidity()) {

                tahapAktif = index;
                tampilkanTahap();

                input.reportValidity();
                return false;
            }
        }

        return true;
    }

    // =====================================================
    // 6. MEMBUAT RINGKASAN FORMULIR
    // =====================================================

    function buatRingkasan() {

        const ringkasan =
            document.getElementById('ringkasanForm');

        ringkasan.replaceChildren();

        function tambahItem(label, nilai) {

            const item = document.createElement('div');
            item.className = 'summary-item';

            const judul = document.createElement('strong');
            judul.textContent = label;

            const isi = document.createElement('span');
            isi.textContent = nilai || 'Belum diisi';

            item.append(judul, isi);
            ringkasan.appendChild(item);
        }

        form.querySelectorAll(
            'input:not([type="checkbox"]), select, textarea'
        ).forEach(function (input) {

            if (input.disabled) return;
            if (input.closest('.repeat-row')) return;

            const container = input.closest(
                '.form-group, .upload-box'
            );

            const label = container?.querySelector('label');

            if (!label) return;

            let nilai = '';

            if (input.type === 'file') {

                nilai = Array.from(input.files)
                    .map(file => file.name)
                    .join(', ');

                if (
                    !nilai &&
                    input.closest('.upload-box')
                        ?.querySelector('[data-existing-document]')
                ) {
                    nilai = 'Menggunakan dokumen sebelumnya';
                }

            } else {
                nilai = input.value;
            }

            tambahItem(
                label.textContent.trim(),
                nilai
            );
        });

        form.querySelectorAll('.repeat-container')
            .forEach(function (container) {

                if (
                    container.closest('.conditional-item')?.hidden
                ) {
                    return;
                }

                container.querySelectorAll('.repeat-row')
                    .forEach(function (row, index) {

                        row.querySelectorAll('input')
                            .forEach(function (input) {

                                if (input.disabled) return;

                                const label = input.closest(
                                    '.repeat-field'
                                )?.querySelector('label');

                                tambahItem(
                                    'Anggota ' + (index + 1) +
                                    ' - ' + (label?.textContent || ''),
                                    input.value
                                );
                            });
                    });
            });
    }

    // =====================================================
    // 7. PREVIEW NAMA FILE
    // =====================================================

    function tampilkanFile(input) {

        const preview = input.closest('.upload-box')
            ?.querySelector('.file-preview');

        if (!preview) return;

        const file = input.files[0];

        if (file) {
            preview.textContent = 'File baru dipilih: ' + file.name;
        } else {
            const adaDokumenLama = !!input.closest('.upload-box')
                ?.querySelector('[data-existing-document]');

            preview.textContent = adaDokumenLama
                ? 'Menggunakan dokumen sebelumnya'
                : 'Belum ada file dipilih';
        }
    }

    // =====================================================
    // 8. VALIDASI SEMUA TAHAP
    // =====================================================

    function validasiSemuaTahap() {

        for (let i = 0; i < steps.length; i++) {

            if (!validasiTahap(i)) {

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                return false;
            }
        }

        return true;
    }

    // =====================================================
    // 9. POPUP KONFIRMASI
    // =====================================================

    function bukaModal() {

        if (!validasiSemuaTahap()) return;

        tahapAktif = steps.length - 1;
        tampilkanTahap();

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');

        btnBatalKirim.focus();
    }

    function tutupModal() {

        if (sedangMengirim) return;

        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');

        btnSelanjutnya.focus();
    }

    // =====================================================
    // 10. PINDAH TAHAP
    // =====================================================

    function ubahTahap(arah) {

        if (
            arah > 0 &&
            tahapAktif === steps.length - 1
        ) {
            bukaModal();
            return;
        }

        if (
            arah > 0 &&
            !validasiTahap(tahapAktif)
        ) {
            return;
        }

        tahapAktif = Math.max(
            0,
            Math.min(
                steps.length - 1,
                tahapAktif + arah
            )
        );

        tampilkanTahap();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // =====================================================
    // 11. INISIALISASI ANGGOTA KELUARGA
    // =====================================================

    form.querySelectorAll('.repeat-container')
        .forEach(function (container) {

            let dataLama = [];

            try {
                dataLama = JSON.parse(
                    container.dataset.oldRows || '[]'
                );
            } catch (error) {
                dataLama = [];
            }

            if (
                !Array.isArray(dataLama) ||
                dataLama.length === 0
            ) {
                tambahAnggota(container);
            } else {

                dataLama.forEach(function (data) {
                    tambahAnggota(container, data);
                });
            }

            container.querySelector('.btn-tambah-anggota')
                .addEventListener('click', function () {
                    tambahAnggota(container);
                });
        });

    // =====================================================
    // 12. EVENT PERUBAHAN INPUT
    // =====================================================

    form.addEventListener('change', function (event) {

        perbaruiKondisi();

        if (event.target.type === 'file') {
            tampilkanFile(event.target);
        }
    });

    form.addEventListener('input', perbaruiKondisi);

    // =====================================================
    // 13. EVENT NAVIGASI
    // =====================================================

    btnSebelumnya.addEventListener('click', function () {
        ubahTahap(-1);
    });

    btnSelanjutnya.addEventListener('click', function () {
        ubahTahap(1);
    });

    btnBatalKirim.addEventListener('click', tutupModal);

    btnYakinKirim.addEventListener('click', function () {

        if (sedangMengirim) return;

        if (!validasiSemuaTahap()) {

            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            return;
        }

        sedangMengirim = true;

        btnYakinKirim.disabled = true;

        btnYakinKirim.textContent = isEdit
            ? 'Mengirim Ulang...'
            : 'Mengirim...';

        HTMLFormElement.prototype.submit.call(form);
    });

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {
            tutupModal();
        }
    });

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            modal.classList.contains('show')
        ) {
            tutupModal();
        }
    });

    // =====================================================
    // 14. TAHAP AWAL
    // =====================================================

    perbaruiKondisi();
    tampilkanTahap();

});
</script>

@endsection