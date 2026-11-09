<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\Surat;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * API pembuatan surat selain Surat Keterangan.
 * Pola tiap jenis surat: validasi -> susun nomor -> simpan lewat simpan().
 * Nama field input mengikuti state frontend (public/app/js/app.js).
 */
class SuratApiController extends Controller
{
    /** Format nomor surat Kematian per format. Urutan sprintf: nomor urut, tahun. */
    private const FORMAT_NOMOR_KEMATIAN = [
        'baru'       => '474.3/%s/403.408.04/%s',
        'lama'       => '474.3/%s/403.408.04/%s',
        'keterangan' => '474.4/%s/403.408.4/%s',
    ];

    /**
     * POST /api/surat/kematian
     * subtype: baru | lama | keterangan (Surat Keterangan Kematian)
     */
    public function storeKematian(Request $request): JsonResponse
    {
        $subtype = $request->validate([
            'subtype' => 'required|in:baru,lama,keterangan',
        ])['subtype'];

        $v = $request->validate(array_merge(
            $this->aturanUmum(),
            $subtype === 'keterangan' ? $this->aturanKeterangan() : $this->aturanBaruLama()
        ));

        $jenis = JenisSurat::where('kode_surat', 'KEM')->first();
        if (! $jenis) {
            return $this->jenisBelumAda('KEM');
        }

        $tanggal = Carbon::parse($v['tanggalSurat'] ?? now());
        $nomor = sprintf(self::FORMAT_NOMOR_KEMATIAN[$subtype], $v['nomorUrut'], $tanggal->year);

        if ($subtype === 'keterangan') {
            // Pemohon = warga yang meninggal; pejabat pemberi keterangan disimpan di data_tambahan
            $pemohon = [
                'nama'   => $v['deathCertNama'],
                'nik'    => $v['deathCertNIK'],
                'alamat' => $v['deathCertAlamat'],
            ];
            $data = [
                'subtype'           => $subtype,
                'umur'              => $v['deathCertUmur'],
                'agama'             => $v['deathCertAgama'],
                'hari'              => $v['deathCertHari'],
                'tanggal_meninggal' => $v['deathCertTanggal'],
                'jam'               => $v['deathCertPukul'],
                'sebab'             => $v['deathCertSebab'],
                'tempat'            => $v['deathCertTempat'],
                'pemberi' => [
                    'nama'    => $v['deathCertPejabatNama'],
                    'alamat'  => $v['deathCertPejabatAlamat'],
                    'jabatan' => $v['deathCertPejabatJabatan'],
                ],
            ];
        } else {
            // Form Surat Kematian baru/lama tidak punya kolom NIK
            $pemohon = [
                'nama'         => $v['nama'],
                'jenisKelamin' => $v['jenisKelamin'],
                'alamat'       => $v['alamat'],
            ];
            $data = [
                'subtype'           => $subtype,
                'umur'              => $v['umur'],
                'hari'              => $v['hari'],
                'jam'               => $v['jam'],
                'tanggal_meninggal' => $v['tanggalMeninggal'],
                'tempat'            => $v['tempat'],
                'sebab'             => $v['sebab'],
            ];
        }

        $data['penandatangan'] = $this->penandatangan($v);

        return $this->simpan($jenis, $nomor, $tanggal, $pemohon, $data);
    }

    // ---------------------------------------------------------------
    // Bagian bersama (dipakai semua jenis surat)
    // ---------------------------------------------------------------

    /** Simpan surat dan beri respons seragam untuk frontend. */
    private function simpan(JenisSurat $jenis, string $nomor, Carbon $tanggal, array $pemohon, array $data): JsonResponse
    {
        try {
            $surat = Surat::create([
                'user_id'        => 1, // sementara; ganti auth()->id() setelah login dibuat
                'jenis_surat_id' => $jenis->id,
                'nomor_surat'    => $nomor,
                'nama_pemohon'   => $pemohon['nama'],
                // kolom nik_pemohon di migration NOT NULL; '' dipakai bila form tidak punya NIK
                'nik_pemohon'    => $pemohon['nik'] ?? '',
                'jenis_kelamin'  => $pemohon['jenisKelamin'] ?? null,
                'alamat_pemohon' => $pemohon['alamat'],
                'data_tambahan'  => $data,
                'tanggal_terbit' => $tanggal,
                'status_arsip'   => 'aktif',
            ]);
        } catch (UniqueConstraintViolationException $e) {
            throw ValidationException::withMessages([
                'nomorUrut' => "Nomor surat {$nomor} sudah digunakan.",
            ]);
        }

        return response()->json([
            'success'     => true,
            'nomor_surat' => $surat->nomor_surat,
            'id'          => $surat->id,
            'pdf_url'     => "/surat/{$surat->id}/pdf",
        ], 201);
    }

    private function jenisBelumAda(string $kode): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => "Jenis surat '{$kode}' belum ada di database. Jalankan: php artisan db:seed --class=JenisSuratSeeder",
        ], 500);
    }

    private function penandatangan(array $v): array
    {
        return [
            'tempat_tanggal' => $v['ttdTempatTanggal'] ?? null,
            'jabatan'        => $v['pejabatJabatan'],
            'nama'           => $v['pejabatNama'],
            'ttd2' => [
                'label'   => $v['ttd2Label'] ?? null,
                'jabatan' => $v['ttd2Jabatan'] ?? null,
                'nama'    => $v['ttd2Nama'] ?? null,
            ],
        ];
    }

    private function aturanUmum(): array
    {
        return [
            'nomorUrut'        => 'required|digits_between:1,6',
            'tanggalSurat'     => 'nullable|date',
            'ttdTempatTanggal' => 'nullable|string|max:255',
            'pejabatJabatan'   => 'required|string|max:255',
            'pejabatNama'      => 'required|string|max:255',
            'ttd2Label'        => 'nullable|string|max:100',
            'ttd2Jabatan'      => 'nullable|string|max:255',
            'ttd2Nama'         => 'nullable|string|max:255',
        ];
    }

    private function aturanBaruLama(): array
    {
        return [
            'nama'             => 'required|string|max:255',
            'jenisKelamin'     => 'required|in:Laki-laki,Perempuan',
            'alamat'           => 'required|string',
            'umur'             => 'required|string|max:50',
            'hari'             => 'required|string|max:50',
            'jam'              => 'required|string|max:50',
            'tanggalMeninggal' => 'required|date',
            'tempat'           => 'required|string|max:255',
            'sebab'            => 'required|string|max:255',
        ];
    }

    private function aturanKeterangan(): array
    {
        return [
            'deathCertPejabatNama'    => 'required|string|max:255',
            'deathCertPejabatAlamat'  => 'required|string',
            'deathCertPejabatJabatan' => 'required|string|max:255',
            'deathCertNama'           => 'required|string|max:255',
            'deathCertNIK'            => 'required|digits:16',
            'deathCertUmur'           => 'required|string|max:50',
            'deathCertAgama'          => 'required|string|max:50',
            'deathCertAlamat'         => 'required|string',
            'deathCertHari'           => 'required|string|max:50',
            'deathCertTanggal'        => 'required|date',
            'deathCertPukul'          => 'required|string|max:50',
            'deathCertSebab'          => 'required|string|max:255',
            'deathCertTempat'         => 'required|string|max:255',
        ];
    }
}