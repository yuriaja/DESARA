
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pelayanan Warga') — DESARA</title>

    <style>
        :root {
            --green: #23694d;
            --green-dark: #173f31;
            --paper: #f7f8f5;
            --ink: #23332c;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font: 15px/1.6 'Segoe UI', sans-serif;
        }

        a {
            color: var(--green);
        }

        .shell {
            display: flex;
            min-height: 100vh;
        }

        .body {
            flex: 1;
            min-width: 0;
            margin-left: var(--sidebar-width);
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            height: 100dvh;
            padding: 28px 18px;
            background: var(--green-dark);
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 10px;
            margin: 8px 8px 36px;
            color: white;
            text-decoration: none;
        }

        .brand-title {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: .04em;
        }

        .brand-subtitle {
            color: #d6e8dc;
            font-size: 11px;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-link,
        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 13px;
            width: 100%;
            min-height: 50px;
            padding: 13px 14px;
            border: none;
            border-radius: 11px;
            background: transparent;
            color: white;
            text-decoration: none;
            font: inherit;
            font-size: 14px;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
        }

        .sidebar-link:hover,
        .sidebar-logout:hover {
            background: rgba(255, 255, 255, .12);
        }

        .sidebar-link.active {
            background: rgba(255, 255, 255, .17);
            font-weight: 600;
        }

        .sidebar-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            width: 4px;
            height: 30px;
            background: #a4d5b3;
            border-radius: 0 5px 5px 0;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            stroke: currentColor;
        }

        .sidebar-divider {
            height: 1px;
            margin: 15px 0;
            background: rgba(255, 255, 255, .15);
        }

        .sidebar-logout-form {
            margin: 0;
        }

        /* HEADER */
        .top {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 30px;
            background: white;
            border-bottom: 1px solid #dce6df;
        }

        .top-title {
            color: #173e30;
            font-size: 16px;
            font-weight: 700;
        }

        .user-avatar {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #d7ecdf;
            color: #206b4c;
            font-size: 15px;
            font-weight: 700;
        }

        /* DROPDOWN PROFIL */
        .profil-dropdown {
            position: relative;
            flex-shrink: 0;
        }

        .profil-trigger {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 7px 10px;
            border-radius: 12px;
            background: #edf6ee;
            color: #206b4c;
            cursor: pointer;
            list-style: none;
            font: inherit;
        }

        .profil-trigger::-webkit-details-marker {
            display: none;
        }

        .profil-trigger::marker {
            content: "";
        }

        .profil-trigger:hover,
        .profil-dropdown[open] .profil-trigger {
            background: #e1f0e6;
        }

        .profil-trigger:focus-visible {
            outline: 2px solid var(--green);
            outline-offset: 3px;
        }

        .profil-dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 12px);
            width: 300px;
            max-width: calc(100vw - 30px);
            padding: 12px;
            background: #fff;
            border: 1px solid #dce7df;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .13);
            z-index: 2500;
        }

        .profil-dropdown-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 9px 17px;
        }

        .profil-dropdown-header .user-avatar {
            width: 48px;
            height: 48px;
            font-size: 19px;
        }

        .profil-dropdown-header strong {
            display: block;
            color: #173e30;
            font-size: 16px;
        }

        .profil-dropdown-header small {
            color: #71837a;
            font-size: 12px;
        }

        .dropdown-divider {
            height: 1px;
            margin: 8px 0;
            background: #e5ece7;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 9px;
            background: transparent;
            color: #23332c;
            text-align: left;
            font: inherit;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #edf6ee;
            color: #206b4c;
        }

        .dropdown-item svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .dropdown-item.logout {
            color: #c43838;
        }

        .dropdown-item.logout:hover {
            background: #fff1f1;
        }

        .profil-dropdown-menu form {
            margin: 0;
        }

        /* KONTEN */
        .main {
            width: 100%;
            max-width: 1350px;
            padding: 30px;
            margin: 0 auto;
        }

        h1 {
            font-size: 29px;
            line-height: 1.2;
            margin: 0 0 12px;
        }

        h2 {
            font-size: 20px;
        }

        .muted {
            color: #62756a;
        }

        /* KOMPONEN DESARA */
        .card {
            background: #fff;
            border: 1px solid #dce7e0;
            border-radius: 15px;
            padding: 22px;
            margin: 14px 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .grid .card {
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 17px;
            border: 0;
            border-radius: 9px;
            background: var(--green);
            color: white;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn:hover {
            background: #174e37;
        }

        .btn.alt {
            background: #eef5f0;
            color: var(--green);
        }

        .btn:disabled {
            background: #aaa;
            cursor: not-allowed;
        }

        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: 600;
        }

        input:not([type="checkbox"]),
        textarea,
        select {
            width: 100%;
            max-width: 100%;
            padding: 11px;
            border: 1px solid #b5c5ba;
            border-radius: 9px;
            background: white;
            font: inherit;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            padding: 0;
            accent-color: var(--green);
        }

        textarea {
            min-height: 90px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .pill {
            display: inline-block;
            border-radius: 99px;
            padding: 2px 11px;
            background: #e6f2ea;
            color: #21553b;
            font-size: 12px;
            font-weight: 700;
        }

        .pill.off {
            background: #f3f1e9;
            color: #7b7460;
        }

        .error {
            margin-bottom: 20px;
            padding: 14px;
            background: #fff2ed;
            border: 1px solid #ffbaa6;
            border-radius: 10px;
        }

        .success {
            margin-bottom: 20px;
            padding: 14px;
            background: #e6f7ed;
            border: 1px solid #a5d8b8;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #e3eae5;
            vertical-align: top;
        }

        .steps {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 22px;
        }

        .steps span {
            padding: 6px 12px;
            border-radius: 25px;
            background: #e8eee9;
        }

        .steps span.active {
            background: var(--green);
            color: white;
        }

        .hidden {
            display: none;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 24px;
        }

        .auth {
            max-width: 520px;
            margin: 55px auto;
        }

        .small {
            font-size: 12px;
        }

        .break {
            overflow-wrap: anywhere;
        }

        button.link {
            padding: 0;
            border: 0;
            background: transparent;
            color: inherit;
            cursor: pointer;
            font: inherit;
        }

        /* RESPONSIVE */
        @media (max-width: 1000px) {
            :root {
                --sidebar-width: 230px;
            }

            .main {
                padding: 24px;
            }

            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .shell {
                display: block;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 16px;
            }

            .brand {
                margin: 2px 8px 18px;
            }

            .sidebar-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .sidebar-link,
            .sidebar-logout {
                width: auto;
                min-height: 42px;
                padding: 10px;
            }

            .sidebar-link.active::before {
                display: none;
            }

            .body {
                margin-left: 0;
            }

            .top {
                padding: 13px 18px;
            }

            .main {
                padding: 18px;
            }
        }

        @media (max-width: 570px) {
            .grid,
            .form-row {
                grid-template-columns: 1fr;
            }

            .main {
                padding: 16px;
            }

            .top-title {
                font-size: 13px;
            }

            .profil-trigger .trigger-name {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="shell">

    {{-- SIDEBAR --}}
    <aside class="sidebar">

        <a
            class="brand"
            href="{{ auth()->check() ? (auth()->user()->role === 'warga' ? route('warga.home') : route('petugas.home')) : route('layanan.login') }}"
        >
            <span class="brand-title">DESARA</span>
            <span class="brand-subtitle">Pelayanan Desa</span>
        </a>

        <nav class="sidebar-nav" aria-label="Navigasi utama">

            @auth

                @if(auth()->user()->role === 'warga')

                    {{-- PELAYANAN ADMINISTRASI --}}
                    <a
                        href="{{ route('warga.home') }}"
                        class="sidebar-link {{ request()->routeIs('warga.home', 'warga.category', 'warga.apply', 'warga.form-kependudukan', 'warga.pratinjau-kependudukan') ? 'active' : '' }}"
                    >
                        <svg
                            class="menu-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        </svg>

                        <span>Pelayanan Administrasi</span>
                    </a>

                    {{-- RIWAYAT PENGAJUAN --}}
                    <a
                        href="{{ route('warga.history') }}"
                        class="sidebar-link {{ request()->routeIs('warga.history', 'warga.detail', 'warga.hasil') ? 'active' : '' }}"
                    >
                        <svg
                            class="menu-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <rect x="4" y="3" width="16" height="18" rx="2"/>
                            <path d="M8 8h8M8 12h8M8 16h5"/>
                        </svg>

                        <span>Riwayat Pengajuan</span>
                    </a>

                @else

                    {{-- PENGAJUAN WARGA --}}
                    <a
                        href="{{ route('petugas.home') }}"
                        class="sidebar-link {{ request()->routeIs('petugas.home') ? 'active' : '' }}"
                    >
                        <svg
                            class="menu-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <rect x="4" y="3" width="16" height="18" rx="2"/>
                            <path d="M8 8h8M8 12h8M8 16h5"/>
                        </svg>

                        <span>Pengajuan Warga</span>
                    </a>

                    {{-- REKAP ADMINISTRASI --}}
                    @if(Route::has('petugas.rekap'))
                        <a
                            href="{{ route('petugas.rekap') }}"
                            class="sidebar-link {{ request()->routeIs('petugas.rekap') ? 'active' : '' }}"
                        >
                            <svg
                                class="menu-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <path d="M3 10h18M8 4v16M12 14h5M12 17h3"/>
                            </svg>

                            <span>Rekap Administrasi</span>
                        </a>
                    @endif

                @endif

                <div class="sidebar-divider"></div>

                {{-- LOGOUT SIDEBAR --}}
                <form
                    class="sidebar-logout-form"
                    method="POST"
                    action="{{ route('layanan.logout') }}"
                >
                    @csrf

                    <button type="submit" class="sidebar-logout">
                        <svg
                            class="menu-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <path d="M16 17l5-5-5-5"/>
                            <path d="M21 12H9"/>
                        </svg>

                        <span>Keluar</span>
                    </button>
                </form>

            @else

                <a href="{{ route('layanan.login') }}" class="sidebar-link">
                    Masuk
                </a>

                <a href="{{ route('layanan.register') }}" class="sidebar-link">
                    Daftar Warga
                </a>

            @endauth

        </nav>

    </aside>

    {{-- KONTEN UTAMA --}}
    <div class="body">

        <header class="top">

            <strong class="top-title">
                Pelayanan Administrasi Desa
            </strong>

            @auth

                <details class="profil-dropdown" id="profilDropdown">

                    <summary class="profil-trigger">

                        <span class="trigger-name">
                            {{ auth()->user()->name }}
                        </span>

                        <span class="user-avatar">
                            {{ strtoupper(mb_substr(auth()->user()->name ?: 'W', 0, 1)) }}
                        </span>

                    </summary>

                    <div class="profil-dropdown-menu">

                        <div class="profil-dropdown-header">

                            <span class="user-avatar">
                                {{ strtoupper(mb_substr(auth()->user()->name ?: 'W', 0, 1)) }}
                            </span>

                            <div>
                                <strong>{{ auth()->user()->name }}</strong>

                                <small>
                                    {{ auth()->user()->role === 'warga'
                                        ? 'Akun Warga DESARA'
                                        : 'Akun Petugas DESARA' }}
                                </small>
                            </div>

                        </div>

                        <div class="dropdown-divider"></div>

                        @if(auth()->user()->role === 'warga')

                            {{-- PROFIL --}}
                            <a
                                class="dropdown-item"
                                href="{{ route('warga.profil') }}#identitas"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                </svg>
                                <span>Profil Saya</span>
                            </a>

                            {{-- PENGATURAN --}}
                            <a
                                class="dropdown-item"
                                href="{{ route('warga.profil') }}#keamanan"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19 12a7 7 0 0 0-.1-1l2-1.6-2-3.4-2.4 1a7 7 0 0 0-1.7-1L14.4 3h-4.8L9.2 6a7 7 0 0 0-1.7 1l-2.4-1-2 3.4L5.1 11a7 7 0 0 0 0 2l-2 1.6 2 3.4 2.4-1a7 7 0 0 0 1.7 1l.4 3h4.8l.4-3a7 7 0 0 0 1.7-1l2.4 1 2-3.4-2-1.6c.1-.3.1-.7.1-1z"/>
                                </svg>

                                <span>Pengaturan Akun</span>
                            </a>

                            {{-- NOTIFIKASI --}}
                            <a
                                class="dropdown-item"
                                href="{{ route('warga.profil') }}#notifikasi"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                                    <path d="M10 21h4"/>
                                </svg>

                                <span>Notifikasi</span>
                            </a>

                            {{-- RINGKASAN --}}
                            <a
                                class="dropdown-item"
                                href="{{ route('warga.profil') }}#ringkasan"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <path d="M8 16v-4M12 16V8M16 16v-6"/>
                                </svg>

                                <span>Ringkasan Pengajuan</span>
                            </a>

                            {{-- RIWAYAT --}}
                            <a
                                class="dropdown-item"
                                href="{{ route('warga.history') }}"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="4" y="3" width="16" height="18" rx="2"/>
                                    <path d="M8 8h8M8 12h8M8 16h5"/>
                                </svg>

                                <span>Riwayat Pengajuan</span>
                            </a>

                        @else

                            {{-- MENU ADMIN --}}
                            <a
                                class="dropdown-item"
                                href="{{ route('petugas.home') }}"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="4" y="3" width="16" height="18" rx="2"/>
                                    <path d="M8 8h8M8 12h8M8 16h5"/>
                                </svg>

                                <span>Pengajuan Warga</span>
                            </a>

                            @if(Route::has('petugas.rekap'))
                                <a
                                    class="dropdown-item"
                                    href="{{ route('petugas.rekap') }}"
                                >
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                                        <path d="M3 10h18M8 4v16M12 14h5M12 17h3"/>
                                    </svg>

                                    <span>Rekap Administrasi</span>
                                </a>
                            @endif

                        @endif

                        <div class="dropdown-divider"></div>

                        {{-- LOGOUT DROPDOWN --}}
                        <form
                            method="POST"
                            action="{{ route('layanan.logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item logout"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <path d="M16 17l5-5-5-5"/>
                                    <path d="M21 12H9"/>
                                </svg>

                                <span>Keluar</span>
                            </button>
                        </form>

                    </div>

                </details>

            @else

                <span class="muted">
                    Selamat datang
                </span>

            @endauth

        </header>

        <main class="main">

            @if(session('success'))
                <div class="success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error" role="alert">
                    <strong>Periksa kembali input berikut:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const dropdown = document.getElementById('profilDropdown');

    if (!dropdown) {
        return;
    }

    // Tutup dropdown jika klik di luar menu.
    document.addEventListener('click', function (event) {

        if (dropdown.open && !dropdown.contains(event.target)) {
            dropdown.open = false;
        }

    });

    // Tutup dropdown jika menekan Escape.
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape' && dropdown.open) {
            dropdown.open = false;
            dropdown.querySelector('summary').focus();
        }

    });

});
</script>

</body>
</html>