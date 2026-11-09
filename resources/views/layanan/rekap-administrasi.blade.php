
@extends('layanan.layout')

@section('title', 'Rekap Administrasi')

@section('content')

<style>
    .rekap-page {
        max-width: 1300px;
        margin: 0 auto;
    }

    .rekap-page h1 {
        color: #173e30;
        margin-bottom: 10px;
    }

    .rekap-description {
        color: #71837a;
        margin-bottom: 25px;
    }

    /* TAB REKAP */
    .rekap-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 25px;
    }

    .rekap-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 13px 18px;
        background: #e7eee9;
        border-radius: 11px;
        color: #206b4c;
        font-weight: 600;
        text-decoration: none;
        transition: .2s;
    }

    .rekap-tab:hover {
        background: #dceae0;
    }

    .rekap-tab.active {
        background: #206b4c;
        color: white;
    }

    /* FILTER TANGGAL */
    .rekap-filter {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px;
        margin-bottom: 25px;
        background: #fff;
        border: 1px solid #dce7df;
        border-radius: 14px;
    }

    .rekap-filter-field {
        flex: 1;
        min-width: 180px;
    }

    .rekap-filter label {
        margin: 0 0 8px;
        font-weight: 600;
    }

    .rekap-filter input {
        width: 100%;
        padding: 11px 13px;
    }

    .rekap-filter .btn {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
    }

    /* KELOMPOK BERDASARKAN TANGGAL */
    .rekap-date-card {
        margin-bottom: 22px;
        background: white;
        border: 1px solid #dce7df;
        border-radius: 15px;
        overflow: hidden;
    }

    .rekap-date-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 17px 22px;
        background: #edf6ee;
        border-bottom: 1px solid #dce7df;
    }

    .rekap-date-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #173e30;
        font-weight: 700;
    }

    .rekap-date-count {
        display: inline-block;
        padding: 5px 12px;
        background: white;
        border-radius: 30px;
        color: #206b4c;
        font-size: 12px;
        font-weight: 700;
    }

    /* TABEL */
    .rekap-table-container {
        padding: 12px 20px 20px;
        overflow-x: auto;
    }

    .rekap-table {
        width: 100%;
        min-width: 1030px;
        border-collapse: collapse;
    }

    .rekap-table th,
    .rekap-table td {
        padding: 16px 12px;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e5ece7;
    }

    .rekap-table th {
        color: #173e30;
        font-size: 14px;
        font-weight: 700;
    }

    .rekap-table tbody tr:last-child td {
        border-bottom: none;
    }

    .rekap-table tbody tr:hover {
        background: #fafcfa;
    }

    .rekap-name {
        color: #173e30;
        font-weight: 700;
    }

    .rekap-number {
        margin-top: 5px;
        color: #819087;
        font-size: 12px;
    }

    .rekap-time {
        margin-top: 4px;
        color: #819087;
        font-size: 12px;
    }

    /* STATUS */
    .rekap-status {
        display: inline-block;
        padding: 7px 13px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-proses {
        background: #e1efff;
        color: #185a9d;
    }

    .status-selesai {
        background: #dff3e5;
        color: #206b4c;
    }

    .status-ditolak {
        background: #fff0f0;
        color: #b33131;
    }

    /* TOMBOL DETAIL DAN PDF */
    .rekap-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 12px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .rekap-link.detail {
        background: #edf6ee;
        color: #206b4c;
    }

    .rekap-link.pdf {
        background: #eef2fa;
        color: #335c91;
    }

    .rekap-link:hover {
        filter: brightness(.96);
    }

    /* KEPUTUSAN */
    .rekap-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .rekap-actions form {
        margin: 0;
    }

    .rekap-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 41px;
        height: 41px;
        padding: 0;
        border: none;
        border-radius: 10px;
        font-size: 21px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        transition: .2s;
    }

    .rekap-action-btn:hover {
        transform: translateY(-1px);
        opacity: .9;
    }

    .rekap-approve {
        background: #206b4c;
    }

    .rekap-reject {
        background: #c24545;
    }

    .rekap-final-text {
        font-size: 13px;
        font-weight: 600;
        color: #206b4c;
    }

    .rekap-reject-reason {
        max-width: 250px;
        margin-top: 8px;
        padding: 10px 12px;
        background: #fff5f5;
        border-radius: 9px;
        color: #aa3030;
        font-size: 12px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .rekap-empty {
        padding: 45px 20px;
        background: white;
        border: 1px dashed #dce7df;
        border-radius: 14px;
        text-align: center;
        color: #71837a;
    }

    .rekap-empty h3 {
        margin: 0 0 8px;
        color: #173e30;
    }

    /* MODAL PENOLAKAN */
    .rekap-modal-overlay {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 35, 25, .55);
        z-index: 9999;
    }

    .rekap-modal-overlay.show {
        display: flex;
    }

    .rekap-modal {
        width: 100%;
        max-width: 490px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 20px 55px rgba(0, 0, 0, .18);
    }

    .rekap-modal h2 {
        margin: 0 0 10px;
        color: #173e30;
    }

    .rekap-modal p {
        color: #71837a;
        line-height: 1.7;
    }

    .rekap-modal textarea {
        width: 100%;
        min-height: 130px;
        margin-top: 8px;
        resize: vertical;
    }

    .rekap-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .rekap-modal-btn {
        padding: 11px 17px;
        border: none;
        border-radius: 9px;
        font: inherit;
        font-weight: 600;
        cursor: pointer;
    }

    .rekap-modal-btn.cancel {
        background: #e8eee9;
        color: #29483b;
    }

    .rekap-modal-btn.reject {
        background: #c24545;
        color: white;
    }

    @media (max-width: 650px) {
        .rekap-date-header {
            padding: 14px;
        }

        .rekap-table-container {
            padding: 10px;
        }

        .rekap-tabs {
            flex-direction: column;
        }

        .rekap-tab {
            justify-content: center;
        }

        .rekap-modal {
            padding: 20px;
        }
    }
</style>

<div class="rekap-page">

    {{-- JUDUL --}}
    <h1>Rekap Administrasi</h1>

    <p class="rekap-description">
        Periksa data dan dokumen pengajuan warga,
        lalu tentukan apakah surat siap dicetak
        atau harus ditolak karena ketidaksesuaian data.
    </p>

    {{-- TAB --}}
    <div class="rekap-tabs">

        <a
            href="{{ route('petugas.rekap', ['tab' => 'proses']) }}"
            class="rekap-tab {{ $tab === 'proses' ? 'active' : '' }}"
        >
            Surat dalam Proses Pengecekan
            ({{ $jumlahProses }})
        </a>

        <a
            href="{{ route('petugas.rekap', ['tab' => 'selesai']) }}"
            class="rekap-tab {{ $tab === 'selesai' ? 'active' : '' }}"
        >
            Surat Telah Selesai
            ({{ $jumlahSelesai }})
        </a>

    </div>

    {{-- FILTER TANGGAL --}}
    <form
        method="GET"
        action="{{ route('petugas.rekap') }}"
        class="rekap-filter"
    >
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div class="rekap-filter-field">
            <label for="tanggal">
                Filter Berdasarkan Tanggal Masuk
            </label>

            <input
                id="tanggal"
                type="date"
                name="tanggal"
                value="{{ $tanggal ?? '' }}"
            >
        </div>

        <button type="submit" class="btn">
            Tampilkan
        </button>

        <a
            href="{{ route('petugas.rekap', ['tab' => $tab]) }}"
            class="btn alt"
        >
            Semua Tanggal
        </a>
    </form>

    {{-- DAFTAR BERDASARKAN TANGGAL --}}
    @forelse($kelompokTanggal as $tanggalMasuk => $daftar)

        <section class="rekap-date-card">

            <div class="rekap-date-header">

                <div class="rekap-date-title">
                    <span>📅</span>

                    <span>
                        {{ \Illuminate\Support\Carbon::parse($tanggalMasuk)->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <span class="rekap-date-count">
                    {{ $daftar->count() }} Surat
                </span>

            </div>

            <div class="rekap-table-container">

                <table class="rekap-table">

                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                            <th>Detail</th>
                            <th>PDF Surat</th>
                            <th>Keputusan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($daftar as $p)

                            @php
                                $pemohon = $p->data_pemohon ?? [];

                                if (is_string($pemohon)) {
                                    $pemohon = json_decode($pemohon, true) ?: [];
                                }

                                $namaPemohon =
                                    $pemohon['nama_pemohon']
                                    ?? $pemohon['nama_lengkap']
                                    ?? $pemohon['nama']
                                    ?? $p->user?->name
                                    ?? 'Nama tidak tersedia';

                                $statusClass = match($p->status) {
                                    'Proses' => 'status-proses',
                                    'Selesai' => 'status-selesai',
                                    'Ditolak' => 'status-ditolak',
                                    default => '',
                                };
                            @endphp

                            <tr>

                                {{-- NAMA LENGKAP --}}
                                <td>
                                    <div class="rekap-name">
                                        {{ $namaPemohon }}
                                    </div>

                                    <div class="rekap-number">
                                        PGJ-{{ str_pad(
                                            $p->id,
                                            5,
                                            '0',
                                            STR_PAD_LEFT
                                        ) }}
                                    </div>
                                </td>

                                {{-- JENIS SURAT --}}
                                <td>
                                    {{ $p->jenis_surat }}
                                </td>

                                {{-- TANGGAL MASUK --}}
                                <td>
                                    {{ $p->created_at->format('d/m/Y') }}

                                    <div class="rekap-time">
                                        {{ $p->created_at->format('H:i') }}
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="rekap-status {{ $statusClass }}">
                                        @if($p->status === 'Selesai')
                                            Selesai
                                        @else
                                            {{ $p->status }}
                                        @endif
                                    </span>

                                    @if($p->status === 'Selesai')
                                        <div class="rekap-final-text" style="margin-top:7px;">
                                            Siap Cetak
                                        </div>
                                    @endif

                                    @if($p->status === 'Ditolak')
                                        <div class="rekap-reject-reason">
                                            <strong>Alasan Penolakan:</strong>

                                            <div>
                                                {{ $p->catatan_petugas ?: 'Tidak ada alasan tercatat.' }}
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                {{-- DETAIL FORM DAN LAMPIRAN --}}
                                <td>
                                    <a
                                        href="{{ route(
                                            'petugas.administrasi.detail',
                                            $p->id
                                        ) }}"
                                        class="rekap-link detail"
                                    >
                                        Lihat Detail
                                    </a>
                                </td>

                                {{-- PDF SURAT --}}
                                <td>
                                    @if(in_array($p->status, ['Proses', 'Selesai'], true))

                                        <a
                                            href="{{ route(
                                                'petugas.administrasi.pdf',
                                                $p->id
                                            ) }}"
                                            class="rekap-link pdf"
                                            target="_blank"
                                            rel="noopener"
                                        >
                                            Lihat PDF
                                        </a>

                                    @else

                                        <span class="muted">—</span>

                                    @endif
                                </td>

                                {{-- KEPUTUSAN --}}
                                <td>

                                    @if($p->status === 'Proses')

                                        <div class="rekap-actions">

                                            {{-- SETUJUI SIAP CETAK --}}
                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'petugas.administrasi.keputusan',
                                                    $p->id
                                                ) }}"
                                                onsubmit="return confirm('Apakah seluruh data dan dokumen sudah sesuai, dan surat dinyatakan siap dicetak?')"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="hidden"
                                                    name="aksi"
                                                    value="selesaikan"
                                                >

                                                <button
                                                    type="submit"
                                                    class="rekap-action-btn rekap-approve"
                                                    title="Setujui, surat siap cetak"
                                                    aria-label="Setujui surat {{ $p->id }} sebagai siap cetak"
                                                >
                                                    ✓
                                                </button>

                                            </form>

                                            {{-- TOLAK --}}
                                            <button
                                                type="button"
                                                class="rekap-action-btn rekap-reject buka-penolakan"
                                                data-url="{{ route(
                                                    'petugas.administrasi.keputusan',
                                                    $p->id
                                                ) }}"
                                                title="Tolak pengajuan"
                                                aria-label="Tolak surat {{ $p->id }}"
                                            >
                                                ✕
                                            </button>

                                        </div>

                                    @elseif($p->status === 'Selesai')

                                        <span class="rekap-final-text">
                                            ✓ Disetujui
                                        </span>

                                    @elseif($p->status === 'Ditolak')

                                        <span style="color:#b33131;font-size:13px;font-weight:600;">
                                            ✕ Ditolak
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </section>

    @empty

        <div class="rekap-empty">

            <h3>Belum Ada Surat</h3>

            <p>
                Tidak ada surat yang ditemukan pada
                kategori atau tanggal yang dipilih.
            </p>

        </div>

    @endforelse

    {{-- POPUP ALASAN PENOLAKAN --}}
    <div
        class="rekap-modal-overlay"
        id="modalPenolakan"
        aria-hidden="true"
    >

        <div
            class="rekap-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="judulPenolakan"
        >

            <h2 id="judulPenolakan">
                Tolak Pengajuan Surat
            </h2>

            <p>
                Jelaskan alasan penolakan surat ini.
                Alasan tersebut akan ditampilkan
                kepada warga melalui Riwayat Pengajuan.
            </p>

            <form
                method="POST"
                id="formPenolakan"
            >
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="aksi"
                    value="tolak"
                >

                <label for="alasanPenolakan">
                    Alasan Penolakan *
                </label>

                <textarea
                    id="alasanPenolakan"
                    name="alasan"
                    minlength="5"
                    maxlength="2000"
                    required
                    placeholder="Contoh: Nama pada formulir tidak sesuai dengan KTP yang dilampirkan."
                ></textarea>

                <div class="rekap-modal-actions">

                    <button
                        type="button"
                        class="rekap-modal-btn cancel"
                        id="batalPenolakan"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rekap-modal-btn reject"
                    >
                        Ya, Tolak Surat
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('modalPenolakan');
    const form = document.getElementById('formPenolakan');
    const alasan = document.getElementById('alasanPenolakan');
    const batal = document.getElementById('batalPenolakan');

    if (!modal || !form || !alasan || !batal) {
        return;
    }

    let tombolTerakhir = null;

    function bukaModal(tombol) {
        tombolTerakhir = tombol;

        form.action = tombol.dataset.url;

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');

        alasan.focus();
    }

    function tutupModal() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');

        form.reset();

        if (tombolTerakhir) {
            tombolTerakhir.focus();
        }
    }

    document.querySelectorAll('.buka-penolakan').forEach(function(tombol) {

        tombol.addEventListener('click', function() {
            bukaModal(tombol);
        });

    });

    batal.addEventListener('click', tutupModal);

    modal.addEventListener('click', function(event) {

        if (event.target === modal) {
            tutupModal();
        }

    });

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape' && modal.classList.contains('show')) {
            tutupModal();
        }

    });

});
</script>

@endsection