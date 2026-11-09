<?php

namespace App\Services;

use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

/**
 * Membuat PDF Surat Kematian dari data yang tersimpan di tabel surats.
 * Tiga format: baru, lama (arsip), keterangan (Surat Keterangan Kematian).
 */
class KematianPdf
{
    public static function make(Surat $surat)
    {
        $d = $surat->data_tambahan ?? [];
        $subtype = $d['subtype'] ?? 'baru';

        if ($subtype === 'keterangan') {
            return Pdf::loadView('surat.kematian_keterangan', self::dataKeterangan($surat, $d));
        }

        return Pdf::loadView('surat.kematian', self::dataBaruLama($surat, $d, $subtype));
    }

    /** Bagian yang sama di semua format: nomor, tanggal surat, penandatangan. */
    private static function dataUmum(Surat $surat, array $d): array
    {
        $ttd = $d['penandatangan'] ?? [];
        $ttd2 = $ttd['ttd2'] ?? [];
        $adaTtd2 = ! empty($ttd2['jabatan']) || ! empty($ttd2['nama']);

        return [
            // Contoh hasil: 474.3/ 567 /403.408.04/2026 (spasi mengikuti template resmi)
            'nomor_tampil'       => preg_replace('#^([^/]+)/([^/]+)/#', '$1/ $2 /', $surat->nomor_surat),
            'tempat_tanggal'     => $ttd['tempat_tanggal'] ?? ('Milangasri, ' . $surat->tanggal_terbit->format('d-m-Y')),
            'jabatan'            => $ttd['jabatan'] ?? '',
            'nama_penandatangan' => $ttd['nama'] ?? '',
            'ttd2'               => $adaTtd2 ? $ttd2 : null,
        ];
    }

    private static function tanggal(?string $nilai): string
    {
        return $nilai ? Carbon::parse($nilai)->format('d-m-Y') : '-';
    }

    private static function dataBaruLama(Surat $surat, array $d, string $subtype): array
    {
        return self::dataUmum($surat, $d) + [
            'judul_atas'        => $subtype === 'lama' ? 'ARSIP DESA MILANGASRI' : 'UNTUK YANG BERSANGKUTAN',
            'nama'              => $surat->nama_pemohon,
            'jenis_kelamin'     => $surat->jenis_kelamin ?? '-',
            'alamat'            => $surat->alamat_pemohon,
            'umur'              => $d['umur'] ?? '-',
            'hari'              => $d['hari'] ?? '-',
            'jam'               => $d['jam'] ?? '-',
            'tanggal_meninggal' => self::tanggal($d['tanggal_meninggal'] ?? null),
            'tempat'            => $d['tempat'] ?? '-',
            'sebab'             => $d['sebab'] ?? '-',
        ];
    }

    private static function dataKeterangan(Surat $surat, array $d): array
    {
        $umum = self::dataUmum($surat, $d);
        $pemberi = $d['pemberi'] ?? [];

        // Bila jabatan/nama penandatangan kosong, pakai bawaan seperti di preview
        $umum['jabatan'] = $umum['jabatan'] ?: 'Kepala Desa Milangasri';
        $umum['nama_penandatangan'] = $umum['nama_penandatangan'] ?: ($pemberi['nama'] ?? '');

        return $umum + [
            'pemberi_nama'      => $pemberi['nama'] ?? '-',
            'pemberi_alamat'    => $pemberi['alamat'] ?? '-',
            'pemberi_jabatan'   => $pemberi['jabatan'] ?? '-',
            'nama'              => $surat->nama_pemohon,
            'nik'               => $surat->nik_pemohon,
            'umur'              => $d['umur'] ?? '-',
            'agama'             => $d['agama'] ?? '-',
            'alamat'            => $surat->alamat_pemohon,
            'hari'              => $d['hari'] ?? '-',
            'tanggal_meninggal' => self::tanggal($d['tanggal_meninggal'] ?? null),
            'pukul'             => $d['jam'] ?? '-',
            'sebab'             => $d['sebab'] ?? '-',
            'tempat'            => $d['tempat'] ?? '-',
        ];
    }
}