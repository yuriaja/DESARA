@extends('layanan.layout')

@section('title', $category->nama)

@section('content')

<style>
    .category-header {
        margin-bottom: 24px;
    }

    .category-header h1 {
        margin-bottom: 10px;
        color: #173e30;
    }

    .category-info {
        background: #edf6ee;
        border: 1px solid #b8dac5;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 22px;
    }

    .category-search {
        margin-bottom: 24px;
    }

    .category-search input {
        width: 100%;
        padding: 13px 16px;
        border: 1px solid #ccd8d1;
        border-radius: 10px;
        font: inherit;
        box-sizing: border-box;
    }

    .category-search input:focus {
        outline: 2px solid #75b99a;
        outline-offset: 1px;
    }

    .category-surat-grid {
        display: grid;
        grid-template-columns: repeat(
            auto-fill,
            minmax(260px, 1fr)
        );
        gap: 20px;
    }

    .category-surat-card {
        display: flex;
        flex-direction: column;
        padding: 24px;
        background: white;
        border: 1px solid #dce7df;
        border-radius: 15px;
        transition: 0.2s ease;
    }

    .category-surat-card:hover {
        border-color: #90bda3;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .category-surat-icon {
        font-size: 32px;
        margin-bottom: 15px;
    }

    .category-surat-card h2 {
        color: #173e30;
        font-size: 18px;
        margin: 0 0 12px;
        line-height: 1.5;
    }

    .category-surat-card p {
        color: #71837a;
        font-size: 14px;
        line-height: 1.7;
    }

    .category-surat-meta {
        margin-top: auto;
        padding-top: 15px;
    }

    .category-surat-meta p {
        margin-bottom: 15px;
    }

    .category-surat-card .btn {
        display: inline-block;
        text-align: center;
        padding: 12px 18px;
        text-decoration: none;
    }

    .category-empty {
        padding: 25px;
        text-align: center;
        color: #71837a;
        background: white;
        border: 1px dashed #ccd8d1;
        border-radius: 12px;
    }

    @media (max-width: 650px) {
        .category-surat-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- BREADCRUMB --}}
<p class="muted">
    <a href="{{ route('warga.home') }}">
        Pelayanan Administrasi
    </a>
    / {{ $category->nama }}
</p>

<div class="category-header">
    <h1>{{ $category->nama }}</h1>

    <p class="muted">
        Pilih layanan administrasi yang ingin diajukan.
    </p>
</div>

@if($category->nama === 'Administrasi Kependudukan')

    {{-- INFORMASI KATEGORI --}}
    <div class="category-info">

        <h2 style="margin-top:0;color:#173e30">
            📋 Layanan Administrasi Kependudukan
        </h2>

        <p class="muted" style="margin-bottom:0">
            Tersedia berbagai jenis layanan surat
            kependudukan. Pilih surat yang dibutuhkan,
            lengkapi formulir, dan unggah dokumen
            persyaratan yang sesuai.
        </p>

    </div>

    {{-- PENCARIAN --}}
    <div class="category-search">

        <input
            type="search"
            id="cariSurat"
            placeholder="Cari nama surat kependudukan..."
            oninput="cariLayanan()"
        >

    </div>

    {{-- DAFTAR 10 SURAT --}}
    <div class="category-surat-grid" id="daftarSurat">

        @foreach(config('desara_kependudukan', []) as $kode => $surat)

            @php
                $namaSurat = $surat['nama']
                    ?? $surat['title']
                    ?? $kode;

                $deskripsi = $surat['summary']
                    ?? 'Layanan administrasi kependudukan desa.';

                $ikon = $surat['icon'] ?? '📄';

                $jumlahDokumen = count(
                    $surat['docs'] ?? $surat['uploads'] ?? []
                );
            @endphp

            <article class="category-surat-card kartu-surat">

                <div class="category-surat-icon">
                    {{ $ikon }}
                </div>

                <h2 class="nama-surat">
                    {{ $namaSurat }}
                </h2>

                <p>
                    {{ $deskripsi }}
                </p>

                <div class="category-surat-meta">

                    <p>
                        📎 {{ $jumlahDokumen }}
                        jenis dokumen persyaratan
                    </p>

                    <a
                        class="btn"
                        href="{{ route('warga.form-kependudukan', ['jenis' => $kode]) }}"
                    >
                        Isi Formulir →
                    </a>

                </div>

            </article>

        @endforeach

    </div>

    <div
        id="pesanTidakDitemukan"
        class="category-empty"
        style="display:none;margin-top:20px"
    >
        Surat yang kamu cari tidak ditemukan.
    </div>

@else

    {{-- PENCARIAN KATEGORI LAIN --}}
    <form method="get" class="category-search">

        <input
            type="search"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama surat..."
        >

        <button
            type="submit"
            class="btn"
            style="margin-top:12px"
        >
            Cari
        </button>

    </form>

    {{-- DAFTAR LAYANAN KATEGORI LAIN --}}
    <div class="category-surat-grid">

        @forelse($services as $s)

            <article class="category-surat-card">

                <div class="category-surat-icon">
                    📄
                </div>

                <h2>{{ $s->nama }}</h2>

                <p>
                    {{ $s->deskripsi }}
                </p>

                <div class="category-surat-meta">

                    <p>
                        <span class="pill {{ $s->status === 'aktif' ? '' : 'off' }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </p>

                    @if(
                        $s->status === 'aktif' &&
                        count($s->form_schema ?? []) > 0
                    )

                        <a
                            class="btn"
                            href="{{ route('warga.apply', $s) }}"
                        >
                            Ajukan Surat →
                        </a>

                    @else

                        <button
                            class="btn"
                            type="button"
                            disabled
                        >
                            Belum Tersedia
                        </button>

                    @endif

                </div>

            </article>

        @empty

            <div class="category-empty">
                Belum ada layanan yang tersedia
                untuk kategori ini.
            </div>

        @endforelse

    </div>

@endif

<script>
function cariLayanan() {

    const input = document.getElementById('cariSurat');

    if (!input) return;

    const keyword = input.value.toLowerCase().trim();

    const cards = document.querySelectorAll('.kartu-surat');

    let ditemukan = 0;

    cards.forEach(function(card) {

        const nama = card.querySelector('.nama-surat')
            .textContent.toLowerCase();

        const deskripsi = card.querySelector('p')
            .textContent.toLowerCase();

        const cocok =
            nama.includes(keyword) ||
            deskripsi.includes(keyword);

        card.style.display = cocok ? '' : 'none';

        if (cocok) ditemukan++;
    });

    const pesan = document.getElementById(
        'pesanTidakDitemukan'
    );

    if (pesan) {
        pesan.style.display =
            ditemukan === 0 ? 'block' : 'none';
    }
}
</script>

@endsection