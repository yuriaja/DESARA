
@extends('layanan.layout')

@section('title', 'Kategori Layanan')

@section('content')

<style>
    .kategori-dashboard .kategori-header {
        margin-bottom: 24px;
    }

    .kategori-dashboard .kategori-header h1 {
        color: #173e30;
        margin: 14px 0;
    }

    .kategori-dashboard .kategori-description {
        margin-bottom: 25px;
        line-height: 1.7;
    }

    .kategori-dashboard .kategori-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .kategori-dashboard .kategori-card h2 {
        margin: 18px 0 12px;
        line-height: 1.5;
        color: #173e30;
    }

    .kategori-dashboard .kategori-icon {
        font-size: 30px;
    }

    /* JUMLAH SURAT DAN TOMBOL SEJAJAR */
    .kategori-dashboard .kategori-footer {
        display: flex !important;
        flex-direction: row !important;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: auto;
        padding-top: 20px;
        width: 100%;
    }

    .kategori-dashboard .jumlah-surat {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 10px;
        border-radius: 30px;
        background: #e6f3eb;
        color: #206b4c;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .kategori-dashboard .kategori-footer .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 12px;
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        text-decoration: none;
        flex-shrink: 0;
    }

    @media (max-width: 1100px) {
        .kategori-dashboard .kategori-footer {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 650px) {
        .kategori-dashboard .kategori-footer {
            flex-direction: row !important;
            flex-wrap: wrap;
        }
    }
</style>

<div class="kategori-dashboard">

    <p class="muted">
        Dashboard / Pelayanan Administrasi
    </p>

    <div class="kategori-header">

        <h1>Mau mengurus administrasi apa?</h1>

        <p class="muted kategori-description">
            Pilih kategori layanan yang dibutuhkan.
            Setiap kategori memiliki berbagai jenis surat
            yang dapat dilihat dan diajukan sesuai
            ketersediaan layanan.
        </p>

    </div>

    <div class="grid">

        @foreach($categories as $category)

            @php
                if ($category->nama === 'Administrasi Kependudukan') {

                    $jumlahSurat = count(
                        config('desara_kependudukan', [])
                    );

                } else {

                    $jumlahSurat = \Illuminate\Support\Facades\DB::table(
                        'layanan_desas'
                    )
                        ->where('layanan_kategori_id', $category->id)
                        ->count();
                }

                $icons = [
                    '🏠',
                    '👶',
                    '💍',
                    '🎓',
                    '🏪',
                    '🏡',
                    '📋',
                ];

                $icon = $icons[$loop->index] ?? '📄';
            @endphp

            <article class="card kategori-card">

                <div class="kategori-icon">
                    {{ $icon }}
                </div>

                <h2>{{ $category->nama }}</h2>

                @if(!empty($category->deskripsi))
                    <p class="muted">
                        {{ $category->deskripsi }}
                    </p>
                @endif

                {{-- FOOTER SEJAJAR --}}
                <div class="kategori-footer">

                    <span class="jumlah-surat">
                        📄 {{ $jumlahSurat }} jenis surat
                    </span>

                    <a
                        class="btn"
                        href="{{ route('warga.category', $category) }}"
                    >
                        Lihat Layanan
                    </a>

                </div>

            </article>

        @endforeach

    </div>
</div>

@endsection