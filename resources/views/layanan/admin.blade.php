
@extends('layanan.layout')

@section('title', 'Pengajuan Warga')

@section('content')

<style>
    .pengajuan-admin {
        max-width: 1250px;
        margin: 0 auto;
    }

    .pengajuan-admin h1 {
        color: #173e30;
        margin-bottom: 10px;
    }

    .pengajuan-admin .description {
        color: #71837a;
        margin-bottom: 25px;
    }

    .pengajuan-admin .table-card {
        background: #fff;
        border: 1px solid #dce7df;
        border-radius: 15px;
        padding: 24px;
        overflow-x: auto;
    }

    .pengajuan-admin table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }

    .pengajuan-admin th,
    .pengajuan-admin td {
        padding: 16px 12px;
        text-align: left;
        border-bottom: 1px solid #e5ece7;
        vertical-align: middle;
    }

    .pengajuan-admin th {
        color: #173e30;
        font-weight: 700;
    }

    .pengajuan-admin tbody tr:last-child td {
        border-bottom: none;
    }

    .pengajuan-admin .nomor {
        font-size: 12px;
        color: #71837a;
        margin-top: 4px;
    }

    .pengajuan-admin .status-antrian {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 30px;
        background: #fff2ce;
        color: #8a6200;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .pengajuan-admin .action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pengajuan-admin .action-group form {
        margin: 0;
    }

    .pengajuan-admin .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 9px 13px;
        border: none;
        border-radius: 9px;
        color: white;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .pengajuan-admin .detail-btn {
        background: #eef5f0;
        color: #206b4c;
    }

    .pengajuan-admin .approve-btn {
        background: #206b4c;
    }

    .pengajuan-admin .reject-btn {
        background: #bd4141;
    }

    .pengajuan-admin .action-btn:hover {
        opacity: .88;
    }

    .pengajuan-admin .empty-state {
        padding: 35px 15px;
        text-align: center;
        color: #71837a;
    }

    /* MODAL PENOLAKAN */
    .pengajuan-admin .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        padding: 20px;
        background: rgba(0, 0, 0, .5);
        align-items: center;
        justify-content: center;
    }

    .pengajuan-admin .modal-overlay.show {
        display: flex;
    }

    .pengajuan-admin .modal-box {
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 26px;
        background: white;
        border-radius: 16px;
    }

    .pengajuan-admin .modal-box h2 {
        margin: 0 0 10px;
        color: #173e30;
    }

    .pengajuan-admin .modal-box p {
        color: #71837a;
        line-height: 1.6;
    }

    .pengajuan-admin .modal-box textarea {
        width: 100%;
        min-height: 120px;
        margin-top: 8px;
    }

    .pengajuan-admin .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .pengajuan-admin .cancel-btn {
        background: #eef1ee;
        color: #29483b;
    }

    @media (max-width: 600px) {
        .pengajuan-admin .table-card {
            padding: 15px;
        }

        .pengajuan-admin h1 {
            font-size: 25px;
        }
    }
</style>

<div class="pengajuan-admin">

    <h1>Daftar Pengajuan Warga</h1>

    <p class="description">
        Periksa surat yang masuk.
        Setujui untuk meneruskan ke proses pengecekan,
        atau tolak pengajuan dengan memberikan alasan.
    </p>

    <div class="table-card">

        <table>
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Jenis Surat</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                    <th>Keputusan</th>
                </tr>
            </thead>

            <tbody>

                @forelse($items as $p)

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

                        $tanggalMasuk = $p->created_at
                            ? $p->created_at->format('d/m/Y H:i')
                            : '-';
                    @endphp

                    <tr>

                        {{-- NAMA PEMOHON --}}
                        <td>
                            <strong>{{ $namaPemohon }}</strong>

                            <div class="nomor">
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
                            {{ $p->jenis_surat ?? '-' }}
                        </td>

                        {{-- TANGGAL MASUK --}}
                        <td>
                            {{ $tanggalMasuk }}
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span class="status-antrian">
                                Dalam Antrian
                            </span>
                        </td>

                        {{-- KEPUTUSAN --}}
                        <td>
                            <div class="action-group">

                                <a
                                    href="{{ route(
                                        'petugas.administrasi.detail',
                                        $p->id
                                    ) }}"
                                    class="action-btn detail-btn"
                                >
                                    Detail
                                </a>

                                {{-- SETUJUI --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'petugas.administrasi.keputusan',
                                        $p->id
                                    ) }}"
                                    onsubmit="return confirm('Setujui pengajuan ini untuk masuk ke proses pengecekan?')"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="aksi"
                                        value="terima"
                                    >

                                    <button
                                        type="submit"
                                        class="action-btn approve-btn"
                                        title="Setujui pengajuan"
                                        aria-label="Setujui pengajuan {{ $p->id }}"
                                    >
                                        ✓
                                    </button>
                                </form>

                                {{-- TOLAK --}}
                                <button
                                    type="button"
                                    class="action-btn reject-btn open-reject"
                                    data-url="{{ route(
                                        'petugas.administrasi.keputusan',
                                        $p->id
                                    ) }}"
                                    title="Tolak pengajuan"
                                    aria-label="Tolak pengajuan {{ $p->id }}"
                                >
                                    ✕
                                </button>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="empty-state">
                            Belum ada pengajuan yang menunggu pemeriksaan.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- POPUP ALASAN PENOLAKAN --}}
    <div
        class="modal-overlay"
        id="rejectModal"
        aria-hidden="true"
    >

        <div
            class="modal-box"
            role="dialog"
            aria-modal="true"
            aria-labelledby="rejectModalTitle"
        >

            <h2 id="rejectModalTitle">
                Tolak Pengajuan Surat
            </h2>

            <p>
                Tuliskan alasan penolakan secara jelas.
                Alasan akan ditampilkan pada riwayat pengajuan warga.
            </p>

            <form
                id="rejectForm"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="aksi"
                    value="tolak"
                >

                <label for="rejectReason">
                    Alasan Penolakan *
                </label>

                <textarea
                    id="rejectReason"
                    name="alasan"
                    minlength="5"
                    maxlength="2000"
                    placeholder="Contoh: Foto KTP tidak jelas sehingga data belum dapat diverifikasi."
                    required
                ></textarea>

                <div class="modal-actions">

                    <button
                        type="button"
                        class="action-btn cancel-btn"
                        id="cancelReject"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="action-btn reject-btn"
                    >
                        Ya, Tolak
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const reason = document.getElementById('rejectReason');
    const cancel = document.getElementById('cancelReject');

    if (!modal || !form || !reason || !cancel) {
        return;
    }

    let lastTrigger = null;

    function openModal(button) {
        lastTrigger = button;
        form.action = button.dataset.url;

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');

        reason.focus();
    }

    function closeModal() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');

        form.reset();

        if (lastTrigger) {
            lastTrigger.focus();
        }
    }

    document.querySelectorAll('.open-reject').forEach(function(button) {
        button.addEventListener('click', function() {
            openModal(button);
        });
    });

    cancel.addEventListener('click', closeModal);

    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });

});
</script>

@endsection