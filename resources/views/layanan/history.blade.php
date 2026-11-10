@extends('layanan.layout')

@section('title', 'Riwayat Pengajuan')

@section('content')

@php
    $pengguna = auth()->user();

    $profilBelumLengkap = $pengguna
        && $pengguna->role === 'warga'
        && (
            blank($pengguna->name)
            || blank($pengguna->nik)
            || blank($pengguna->email)
            || blank($pengguna->tempat_lahir)
            || blank($pengguna->tanggal_lahir)
            || blank($pengguna->jenis_kelamin)
            || blank($pengguna->telepon)
        );

    $tampilkanPengingat = $profilBelumLengkap
        && !session('profil_diingatkan');
@endphp

@if($tampilkanPengingat)
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:16px;
        padding:20px;
        margin-bottom:24px;
        background:#edf6ee;
        border:1px solid #b8dac5;
        border-radius:14px;
    ">
        <div>
            <strong style="color:#173e30;">
                Lengkapi Profil Anda
            </strong>

            <p style="margin:6px 0 0;color:#62756a;">
                Profil Anda belum lengkap.
                Lengkapi identitas agar proses pelayanan
                administrasi menjadi lebih mudah.
            </p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <form
                method="POST"
                action="{{ route('warga.profil.nanti') }}"
            >
                @csrf

                <button type="submit" class="btn alt">
                    Nanti Saja
                </button>
            </form>

            <a
                href="{{ route('warga.profil') }}"
                class="btn"
            >
                Lengkapi Profil
            </a>
        </div>
    </div>
@endif


<style>
    .history-reason {
        display: block;
        max-width: 240px;
        color: #a12828;
        font-size: 13px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .history-edit-btn {
        display: inline-block;
        padding: 10px 15px;
        border-radius: 9px;
        background: #206b4c;
        color: #ffffff !important;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .history-edit-btn:hover {
        background: #174e37;
    }

    .history-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .history-page h1 {
        margin-bottom: 10px;
        color: #173e30;
        font-size: 30px;
    }

    .history-description {
        margin-bottom: 25px;
        color: #71837a;
        line-height: 1.6;
    }

    .history-card {
        background: #ffffff;
        border: 1px solid #dce7df;
        border-radius: 16px;
        padding: 28px;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .history-table th,
    .history-table td {
        padding: 17px 14px;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e5ece7;
    }

    .history-table th {
        color: #173e30;
        font-weight: 700;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .history-number {
        color: #206b4c;
        font-weight: 700;
        white-space: nowrap;
    }

    .history-status {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-antrian {
        color: #986800;
        background: #fff2ce;
    }

    .status-proses {
        color: #185a9d;
        background: #e1efff;
    }

    .status-selesai {
        color: #206b4c;
        background: #dff3e5;
    }

    .status-lain {
        color: #687a70;
        background: #eef1ee;
    }

    .history-empty {
        text-align: center;
        padding: 35px 15px;
        color: #71837a;
    }

    .history-pagination {
        margin-top: 22px;
    }

    .history-info {
        padding: 16px 20px;
        margin-bottom: 20px;
        border: 1px solid #b8dac5;
        border-radius: 12px;
        background: #edf6ee;
        color: #29483b;
        line-height: 1.6;
    }

    @media (max-width: 700px) {
        .history-card {
            padding: 16px;
        }

        .history-page h1 {
            font-size: 25px;
        }
    }
</style>

<div class="history-page">

    <h1>Riwayat Pengajuan Saya</h1>

    <p class="history-description">
        Pantau perkembangan surat yang telah diajukan
        melalui layanan administrasi DESARA.
    </p>

    <div class="history-info">
        <strong>Informasi Status Pengajuan</strong>

        <p style="margin:8px 0 0;">
            <strong>Dalam Antrian</strong> berarti menunggu pemeriksaan,
            <strong>Proses</strong> berarti sedang dikerjakan petugas,
            dan <strong>Selesai</strong> berarti pengajuan sudah selesai diproses.
        </p>
    </div>

    <div class="history-card">

        <table class="history-table">

            <thead>
                <tr>
                    <th>Nomor Pengajuan</th>
                    <th>Jenis Surat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Alasan Penolakan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($items as $p)

                    @php
                        $status = $p->status;

                        if ($status === 'Diajukan') {
                            $status = 'Dalam Antrian';
                        }

                        $statusClass = match ($status) {
                            'Dalam Antrian' => 'status-antrian',
                            'Proses' => 'status-proses',
                            'Selesai' => 'status-selesai',
                            default => 'status-lain',
                        };
                    @endphp

                    <tr>

                        <td>
                            <span class="history-number">
                                PGJ-{{ str_pad(
                                    $p->id,
                                    5,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}
                            </span>
                        </td>

                        <td>
                            {{ $p->jenis_surat }}
                        </td>

                        <td>
                            {{ $p->created_at
                                ? $p->created_at->format('d/m/Y H:i')
                                : '-' }}
                        </td>
                        
                       {{-- STATUS --}}
                        <td>
                            <span class="history-status {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>

                        {{-- ALASAN PENOLAKAN --}}
                        <td>
                            @if($p->status === 'Ditolak')
                                <span class="history-reason">
                                    {{ $p->catatan_petugas ?: 'Alasan belum tersedia.' }}
                                </span>
                            @else
                                <span style="color:#a0aaa4;">—</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td>
                            @if($p->status === 'Ditolak')
                                <a
                                    href="{{ route('warga.kependudukan.edit', $p) }}"
                                    class="history-edit-btn"
                                >
                                    <span>✎</span> Perbaiki
                                </a>
                            @else
                                <span style="color:#a0aaa4;">—</span>
                            @endif
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="history-empty">
                            Belum ada pengajuan surat.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        @if($items->hasPages())
            <div class="history-pagination">
                {{ $items->links() }}
            </div>
        @endif

    </div>

</div>

@endsection