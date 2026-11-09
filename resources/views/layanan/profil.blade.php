
@extends('layanan.layout')

@section('title', 'Profil Saya')

@section('content')

@php
    $user = auth()->user();
    $inisial = strtoupper(mb_substr($user->name ?: 'W', 0, 1));

    $jumlahAntrian = \App\Models\PengajuanSuratKependudukan::where(
        'user_id', $user->id
    )->whereIn('status', ['Dalam Antrian', 'Diajukan'])->count();

    $jumlahProses = \App\Models\PengajuanSuratKependudukan::where(
        'user_id', $user->id
    )->where('status', 'Proses')->count();

    $jumlahSelesai = \App\Models\PengajuanSuratKependudukan::where(
        'user_id', $user->id
    )->where('status', 'Selesai')->count();

    $totalPengajuan = \App\Models\PengajuanSuratKependudukan::where(
        'user_id', $user->id
    )->count();

    $lengkap = filled($user->name)
        && filled($user->nik)
        && filled($user->email)
        && filled($user->tempat_lahir)
        && filled($user->tanggal_lahir)
        && filled($user->jenis_kelamin)
        && filled($user->telepon);

    $tanggalLahir = $user->tanggal_lahir
        ? \Illuminate\Support\Carbon::parse($user->tanggal_lahir)->format('Y-m-d')
        : '';

    $errorKeamanan = $errors->has('password_lama')
        || $errors->has('password_baru')
        || $errors->has('password_baru_confirmation');
@endphp

<style>
    .profil-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .profil-page * {
        box-sizing: border-box;
    }

    .profil-breadcrumb {
        margin-bottom: 15px;
        font-size: 14px;
        color: #71837a;
    }

    .profil-title {
        margin-bottom: 8px;
        font-size: 29px;
        color: #173e30;
    }

    .profil-subtitle {
        margin-bottom: 25px;
        color: #71837a;
    }

    .profil-header {
        display: flex;
        align-items: center;
        gap: 22px;
        padding: 28px;
        margin-bottom: 24px;
        background: white;
        border: 1px solid #dce7df;
        border-radius: 16px;
    }

    .profil-avatar {
        width: 85px;
        height: 85px;
        min-width: 85px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e4f2e9;
        border-radius: 50%;
        color: #206b4c;
        font-size: 32px;
        font-weight: 800;
    }

    .profil-header-info {
        min-width: 0;
        flex: 1;
    }

    .profil-header-info h2 {
        margin: 0 0 5px;
        font-size: 24px;
        color: #173e30;
    }

    .profil-header-info p {
        margin: 0 0 10px;
        color: #71837a;
        overflow-wrap: anywhere;
    }

    .profil-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 30px;
        background: #fff2ce;
        color: #886000;
        font-size: 12px;
        font-weight: 600;
    }

    .profil-status.lengkap {
        background: #dff3e5;
        color: #206b4c;
    }

    .profil-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 20px;
        border-bottom: 1px solid #dce7df;
    }

    .profil-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 13px 17px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #62756a;
        font: inherit;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
    }

    .profil-tab:hover {
        background: #edf6ee;
    }

    .profil-tab.active {
        background: #206b4c;
        color: #fff;
    }

    .profil-section {
        display: none;
    }

    .profil-section.active {
        display: block;
    }

    .profil-section[hidden] {
        display: none !important;
    }

    .profil-card {
        padding: 28px;
        margin-bottom: 20px;
        background: #fff;
        border: 1px solid #dce7df;
        border-radius: 16px;
    }

    .profil-card h2 {
        margin: 0 0 8px;
        font-size: 21px;
        color: #173e30;
    }

    .profil-card-description {
        margin: 0 0 24px;
        color: #71837a;
        font-size: 14px;
        line-height: 1.7;
    }

    .profil-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .profil-field {
        min-width: 0;
    }

    .profil-field.full {
        grid-column: 1 / -1;
    }

    .profil-field label {
        display: block;
        margin: 0 0 8px;
        color: #18392e;
        font-weight: 600;
    }

    .profil-field input,
    .profil-field select {
        display: block;
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccd8d1;
        border-radius: 9px;
        background: white;
        color: #18392e;
        font: inherit;
    }

    .profil-field input:focus,
    .profil-field select:focus {
        outline: 2px solid #75b99a;
        outline-offset: 1px;
    }

    .profil-field input[readonly] {
        background: #f3f6f3;
        color: #61746a;
        cursor: default;
    }

    .profil-help {
        margin: 6px 0 0;
        font-size: 12px;
        color: #819087;
    }

    .profil-required {
        color: #c43838;
    }

    .profil-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
    }

    .profil-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 22px;
        border: none;
        border-radius: 9px;
        background: #206b4c;
        color: white;
        text-decoration: none;
        font: inherit;
        font-weight: 600;
        cursor: pointer;
    }

    .profil-btn:hover {
        background: #174e37;
        color: white;
    }

    .profil-info {
        padding: 16px 20px;
        border: 1px solid #b8dac5;
        border-radius: 12px;
        background: #edf6ee;
        color: #29483b;
        line-height: 1.7;
    }

    .profil-info p {
        margin: 6px 0 0;
    }

    .profil-security-note {
        margin-top: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #f8faf8;
        color: #71837a;
        font-size: 13px;
    }

    .profil-notification {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        border: 1px solid #dce7df;
        border-radius: 12px;
        background: #f8fbf8;
    }

    .profil-notification-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        flex-shrink: 0;
        border-radius: 11px;
        background: #e4f2e9;
        color: #206b4c;
    }

    .profil-notification-icon svg {
        width: 23px;
        height: 23px;
    }

    .profil-notification h3 {
        margin: 0 0 6px;
        color: #173e30;
        font-size: 16px;
    }

    .profil-notification p {
        margin: 0;
        color: #71837a;
        font-size: 14px;
        line-height: 1.7;
    }

    .profil-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .profil-stat {
        padding: 22px;
        border: 1px solid #dce7df;
        border-radius: 13px;
        background: #f8fbf8;
    }

    .profil-stat.antrian {
        background: #fffaf0;
    }

    .profil-stat.proses {
        background: #f0f7ff;
    }

    .profil-stat.selesai {
        background: #edf7f0;
    }

    .profil-stat.total {
        background: #f5f7f5;
    }

    .profil-stat-title {
        margin-bottom: 12px;
        font-size: 13px;
        color: #71837a;
    }

    .profil-stat-number {
        font-size: 32px;
        font-weight: 800;
        line-height: 1.2;
        color: #173e30;
    }

    @media (max-width: 800px) {
        .profil-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .profil-header {
            padding: 20px;
            gap: 15px;
        }

        .profil-avatar {
            width: 65px;
            height: 65px;
            min-width: 65px;
            font-size: 25px;
        }

        .profil-header-info h2 {
            font-size: 19px;
        }

        .profil-card {
            padding: 20px;
        }

        .profil-form-grid {
            grid-template-columns: 1fr;
        }

        .profil-field.full {
            grid-column: auto;
        }

        .profil-tabs {
            gap: 5px;
        }

        .profil-tab {
            padding: 11px 13px;
            font-size: 13px;
        }

        .profil-actions {
            flex-wrap: wrap;
        }
    }
</style>

<div class="profil-page">

    <p class="profil-breadcrumb">
        <a href="{{ route('warga.home') }}">Dashboard</a>
        / Profil Saya
    </p>

    <h1 class="profil-title">
        Profil & Pengaturan Akun
    </h1>

    <p class="profil-subtitle">
        Kelola informasi pribadi, keamanan akun,
        dan pantau aktivitas pengajuan surat.
    </p>

    <div class="profil-header">

        <div class="profil-avatar">
            {{ $inisial }}
        </div>

        <div class="profil-header-info">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>

            <span class="profil-status {{ $lengkap ? 'lengkap' : '' }}">
                {{ $lengkap ? '✓ Profil Lengkap' : '⚠ Profil Belum Lengkap' }}
            </span>
        </div>

    </div>

    {{-- TAB NAVIGASI --}}
    <div
        class="profil-tabs"
        role="tablist"
        aria-label="Pengaturan Profil"
    >

        <button
            type="button"
            class="profil-tab active"
            data-tab="identitas"
            role="tab"
            aria-selected="true"
            aria-controls="tab-identitas"
        >
            Identitas Pribadi
        </button>

        <button
            type="button"
            class="profil-tab"
            data-tab="keamanan"
            role="tab"
            aria-selected="false"
            aria-controls="tab-keamanan"
        >
            Keamanan Akun
        </button>

        <button
            type="button"
            class="profil-tab"
            data-tab="notifikasi"
            role="tab"
            aria-selected="false"
            aria-controls="tab-notifikasi"
        >
            Notifikasi
        </button>

        <button
            type="button"
            class="profil-tab"
            data-tab="ringkasan"
            role="tab"
            aria-selected="false"
            aria-controls="tab-ringkasan"
        >
            Ringkasan Pengajuan
        </button>

    </div>

    {{-- TAB 1: IDENTITAS --}}
    <section
        class="profil-section active"
        id="tab-identitas"
        role="tabpanel"
    >

        <div class="profil-card">

            <h2>Identitas Pribadi</h2>

            <p class="profil-card-description">
                Nama, NIK, dan email berasal dari registrasi.
                Lengkapi informasi pribadi lainnya di bawah ini.
            </p>

            <form
                method="POST"
                action="{{ route('warga.profil.update') }}"
            >
                @csrf
                @method('PUT')

                <div class="profil-form-grid">

                    <div class="profil-field">
                        <label for="nama">Nama Lengkap</label>

                        <input
                            id="nama"
                            type="text"
                            value="{{ $user->name }}"
                            readonly
                        >

                        <p class="profil-help">
                            Sesuai data akun.
                        </p>
                    </div>

                    <div class="profil-field">
                        <label for="nik">NIK</label>

                        <input
                            id="nik"
                            type="text"
                            value="{{ $user->nik }}"
                            readonly
                        >

                        <p class="profil-help">
                            Nomor identitas pemilik akun.
                        </p>
                    </div>

                    <div class="profil-field full">
                        <label for="email">Email</label>

                        <input
                            id="email"
                            type="email"
                            value="{{ $user->email }}"
                            readonly
                        >
                    </div>

                    <div class="profil-field">
                        <label for="tempat_lahir">
                            Tempat Lahir
                            <span class="profil-required">*</span>
                        </label>

                        <input
                            id="tempat_lahir"
                            name="tempat_lahir"
                            type="text"
                            value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                            placeholder="Contoh: Surabaya"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="profil-field">
                        <label for="tanggal_lahir">
                            Tanggal Lahir
                            <span class="profil-required">*</span>
                        </label>

                        <input
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            type="date"
                            value="{{ old('tanggal_lahir', $tanggalLahir) }}"
                            max="{{ date('Y-m-d') }}"
                            required
                        >
                    </div>

                    <div class="profil-field">
                        <label for="jenis_kelamin">
                            Jenis Kelamin
                            <span class="profil-required">*</span>
                        </label>

                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            required
                        >
                            <option value="">
                                -- Pilih Jenis Kelamin --
                            </option>

                            <option
                                value="Laki-laki"
                                @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'Laki-laki')
                            >
                                Laki-laki
                            </option>

                            <option
                                value="Perempuan"
                                @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'Perempuan')
                            >
                                Perempuan
                            </option>
                        </select>
                    </div>

                    <div class="profil-field">
                        <label for="telepon">
                            Nomor HP Aktif
                            <span class="profil-required">*</span>
                        </label>

                        <input
                            id="telepon"
                            name="telepon"
                            type="tel"
                            value="{{ old('telepon', $user->telepon) }}"
                            placeholder="08xxxxxxxxxx"
                            pattern="08[0-9]{8,13}"
                            required
                        >

                        <p class="profil-help">
                            Masukkan nomor HP yang masih aktif.
                        </p>
                    </div>

                </div>

                <div class="profil-actions">
                    <button type="submit" class="profil-btn">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="profil-info">
            <strong>Informasi Penting</strong>
            <p>
                Profil menyimpan identitas pemilik akun.
                Saat membantu orang lain mengajukan surat,
                identitas orang yang dibuatkan surat
                tetap diisi secara terpisah.
            </p>
        </div>

    </section>

    {{-- TAB 2: KEAMANAN --}}
    <section
        class="profil-section"
        id="tab-keamanan"
        role="tabpanel"
        hidden
    >

        <div class="profil-card">

            <h2>Keamanan Akun</h2>

            <p class="profil-card-description">
                Ubah password akun untuk menjaga keamanan data DESARA.
            </p>

            <form
                method="POST"
                action="{{ route('warga.profil.password') }}"
            >
                @csrf
                @method('PUT')

                <div class="profil-form-grid">

                    <div class="profil-field full">
                        <label for="password_lama">
                            Password Lama
                            <span class="profil-required">*</span>
                        </label>

                        <input
                            id="password_lama"
                            name="password_lama"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Masukkan password saat ini"
                            required
                        >
                    </div>

                    <div class="profil-field">
                        <label for="password_baru">
                            Password Baru
                            <span class="profil-required">*</span>
                        </label>

                        <input
                            id="password_baru"
                            name="password_baru"
                            type="password"
                            autocomplete="new-password"
                            minlength="8"
                            placeholder="Minimal 8 karakter"
                            required
                        >
                    </div>

                    <div class="profil-field">
                        <label for="password_baru_confirmation">
                            Konfirmasi Password Baru
                            <span class="profil-required">*</span>
                        </label>

                        <input
                            id="password_baru_confirmation"
                            name="password_baru_confirmation"
                            type="password"
                            autocomplete="new-password"
                            minlength="8"
                            placeholder="Ulangi password baru"
                            required
                        >
                    </div>

                </div>

                <div class="profil-actions">
                    <button type="submit" class="profil-btn">
                        Ubah Password
                    </button>
                </div>
            </form>

            <div class="profil-security-note">
                Gunakan password minimal 8 karakter dan
                jangan membagikannya kepada orang lain.
            </div>
        </div>

        <div class="profil-card">

            <h2>Verifikasi Email</h2>

            <p class="profil-card-description">
                Informasi status verifikasi email akun.
            </p>

            @if($user->email_verified_at)

                <span class="profil-status lengkap">
                    ✓ Email Terverifikasi
                </span>

            @else

                <span class="profil-status">
                    Email Belum Terverifikasi
                </span>

                <p class="profil-help" style="margin-top:12px">
                    Fitur pengiriman email verifikasi
                    belum tersedia pada halaman ini.
                </p>

            @endif

        </div>

    </section>

    {{-- TAB 3: NOTIFIKASI --}}
    <section
        class="profil-section"
        id="tab-notifikasi"
        role="tabpanel"
        hidden
    >

        <div class="profil-card">

            <h2>Notifikasi Pengajuan</h2>

            <p class="profil-card-description">
                Pantau perubahan status pengajuan administrasi desa.
            </p>

            <div class="profil-notification">

                <div class="profil-notification-icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                    </svg>
                </div>

                <div>
                    <h3>Perubahan Status Surat</h3>

                    <p>
                        Status pengajuan terdiri atas
                        Dalam Antrian, Proses, dan Selesai.
                        Perkembangannya dapat dipantau melalui
                        Riwayat Pengajuan.
                    </p>

                    <p style="margin-top:10px">
                        Pengiriman notifikasi otomatis
                        belum diaktifkan. Bagian ini
                        sementara menampilkan informasi
                        status pelayanan.
                    </p>
                </div>

            </div>

            <div class="profil-actions">
                <a
                    href="{{ route('warga.history') }}"
                    class="profil-btn"
                >
                    Lihat Riwayat Pengajuan
                </a>
            </div>

        </div>

    </section>

    {{-- TAB 4: RINGKASAN --}}
    <section
        class="profil-section"
        id="tab-ringkasan"
        role="tabpanel"
        hidden
    >

        <div class="profil-card">

            <h2>Ringkasan Pengajuan Surat</h2>

            <p class="profil-card-description">
                Jumlah pengajuan kependudukan yang tercatat
                menggunakan akun DESARA ini.
            </p>

            <div class="profil-stats">

                <div class="profil-stat antrian">
                    <div class="profil-stat-title">
                        Dalam Antrian
                    </div>
                    <div class="profil-stat-number">
                        {{ $jumlahAntrian }}
                    </div>
                </div>

                <div class="profil-stat proses">
                    <div class="profil-stat-title">
                        Proses
                    </div>
                    <div class="profil-stat-number">
                        {{ $jumlahProses }}
                    </div>
                </div>

                <div class="profil-stat selesai">
                    <div class="profil-stat-title">
                        Selesai
                    </div>
                    <div class="profil-stat-number">
                        {{ $jumlahSelesai }}
                    </div>
                </div>

                <div class="profil-stat total">
                    <div class="profil-stat-title">
                        Total Pengajuan
                    </div>
                    <div class="profil-stat-number">
                        {{ $totalPengajuan }}
                    </div>
                </div>

            </div>

            <div class="profil-info">
                <strong>Informasi Pengajuan</strong>
                <p>
                    Data ringkasan diambil dari tabel
                    pengajuan_surat_kependudukans
                    berdasarkan akun yang sedang login.
                </p>
            </div>

            <div class="profil-actions">
                <a
                    href="{{ route('warga.history') }}"
                    class="profil-btn"
                >
                    Lihat Semua Pengajuan
                </a>
            </div>

        </div>

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabs = Array.from(
        document.querySelectorAll('.profil-tab')
    );

    const sections = Array.from(
        document.querySelectorAll('.profil-section')
    );

    const validTabs = [
        'identitas',
        'keamanan',
        'notifikasi',
        'ringkasan'
    ];

    const adaErrorKeamanan = @json($errorKeamanan);

    function bukaTab(nama) {

        if (!validTabs.includes(nama)) {
            nama = 'identitas';
        }

        tabs.forEach(function (tab) {

            const aktif = tab.dataset.tab === nama;

            tab.classList.toggle('active', aktif);
            tab.setAttribute(
                'aria-selected',
                aktif ? 'true' : 'false'
            );

            tab.tabIndex = aktif ? 0 : -1;
        });

        sections.forEach(function (section) {

            const aktif = section.id === 'tab-' + nama;

            section.classList.toggle('active', aktif);
            section.hidden = !aktif;
        });
    }

    // Klik tab pada halaman profil.
    tabs.forEach(function (tab) {

        tab.addEventListener('click', function () {

            const nama = tab.dataset.tab;

            bukaTab(nama);

            // Gunakan hash agar shortcut dropdown juga bekerja.
            if (window.location.hash !== '#' + nama) {
                window.location.hash = nama;
            }
        });
    });

    // Shortcut dropdown menuju tab melalui URL.
    window.addEventListener('hashchange', function () {

        const nama = window.location.hash.substring(1);

        bukaTab(nama);
    });

    // Tab ketika halaman pertama kali dibuka.
    const hashAwal = window.location.hash.substring(1);

    if (validTabs.includes(hashAwal)) {

        bukaTab(hashAwal);

    } else if (adaErrorKeamanan) {

        bukaTab('keamanan');

    } else {

        bukaTab('identitas');

    }

});
</script>

@endsection