<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSuratKependudukan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdministrasiKependudukanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AKSES ADMIN
    |--------------------------------------------------------------------------
    */

    private function admin(Request $request): void
    {
        abort_unless(
            $request->user()
                && $request->user()->role === 'admin',
            403,
            'Akses hanya untuk admin.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 1. PENGAJUAN WARGA
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $this->admin($request);

        $items = PengajuanSuratKependudukan::query()
            ->with('user')
            ->whereIn('status', [
                'Dalam Antrian',
                'Diajukan',
            ])
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view('layanan.admin', compact('items'));
    }

    /*
    |--------------------------------------------------------------------------
    | 2. REKAP ADMINISTRASI
    |--------------------------------------------------------------------------
    */

    public function rekap(Request $request)
    {
        $this->admin($request);

        $tab = $request->query('tab', 'proses');

        if (!in_array($tab, ['proses', 'selesai'], true)) {
            $tab = 'proses';
        }

        $query = PengajuanSuratKependudukan::query()
            ->with('user');

        if ($tab === 'proses') {
            $query->where('status', 'Proses');
        } else {
            $query->whereIn('status', [
                'Selesai',
                'Ditolak',
            ]);
        }

        $tanggal = $request->query('tanggal');

        if ($tanggal) {
            $request->validate([
                'tanggal' => ['date_format:Y-m-d'],
            ]);

            $query->whereDate('created_at', $tanggal);
        }

        $items = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $kelompokTanggal = $items->groupBy(
            fn ($item) => $item->created_at->format('Y-m-d')
        );

        $jumlahProses = PengajuanSuratKependudukan::query()
            ->where('status', 'Proses')
            ->count();

        $jumlahSelesai = PengajuanSuratKependudukan::query()
            ->whereIn('status', [
                'Selesai',
                'Ditolak',
            ])
            ->count();

        return view(
            'layanan.rekap-administrasi',
            compact(
                'tab',
                'tanggal',
                'kelompokTanggal',
                'jumlahProses',
                'jumlahSelesai'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3. DETAIL PENGAJUAN
    |--------------------------------------------------------------------------
    */

    public function detail(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ) {
        $this->admin($request);

        return view(
            'layanan.detail-administrasi',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 4. KEPUTUSAN ADMIN
    |--------------------------------------------------------------------------
    |
    | terima:
    | Dalam Antrian / Diajukan -> Proses
    |
    | tolak:
    | Dalam Antrian / Diajukan / Proses -> Ditolak
    |
    | selesaikan:
    | Proses -> Selesai
    | Wajib mengisi nomor surat dan nama kepala desa.
    |
    */

    public function keputusan(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ) {
        $this->admin($request);

        $validated = $request->validate([
            'aksi' => [
                'required',
                Rule::in([
                    'terima',
                    'tolak',
                    'selesaikan',
                ]),
            ],

            'alasan' => [
                Rule::requiredIf(
                    $request->input('aksi') === 'tolak'
                ),
                'nullable',
                'string',
                'min:5',
                'max:2000',
            ],

            'nomor_surat' => [
                Rule::requiredIf(
                    $request->input('aksi') === 'selesaikan'
                ),
                'nullable',
                'string',
                'max:100',
            ],

            'nama_kepala_desa' => [
                Rule::requiredIf(
                    $request->input('aksi') === 'selesaikan'
                ),
                'nullable',
                'string',
                'max:150',
            ],
        ], [
            'nomor_surat.required' =>
                'Nomor surat wajib diisi sebelum surat diselesaikan.',

            'nama_kepala_desa.required' =>
                'Nama kepala desa wajib diisi sebelum surat diselesaikan.',

            'alasan.required' =>
                'Alasan penolakan wajib diisi.',
        ]);

        $pesan = DB::transaction(function () use (
            $pengajuan,
            $validated
        ) {
            $data = PengajuanSuratKependudukan::query()
                ->whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $status = $data->status;
            $aksi = $validated['aksi'];

            /*
            |--------------------------------------------------------------------------
            | A. TERIMA PENGAJUAN AWAL
            |--------------------------------------------------------------------------
            */

            if ($aksi === 'terima') {

                if (!in_array(
                    $status,
                    ['Dalam Antrian', 'Diajukan'],
                    true
                )) {
                    throw ValidationException::withMessages([
                        'aksi' =>
                            'Surat ini sudah tidak berada dalam antrean.',
                    ]);
                }

                $data->status = 'Proses';
                $data->catatan_petugas = null;

                $data->save();

                return 'Pengajuan berhasil diterima dan masuk ke proses pengecekan.';
            }

            /*
            |--------------------------------------------------------------------------
            | B. TOLAK PENGAJUAN
            |--------------------------------------------------------------------------
            */

            if ($aksi === 'tolak') {

                if (!in_array(
                    $status,
                    [
                        'Dalam Antrian',
                        'Diajukan',
                        'Proses',
                    ],
                    true
                )) {
                    throw ValidationException::withMessages([
                        'aksi' =>
                            'Surat tidak dapat ditolak pada status ini.',
                    ]);
                }

                $data->status = 'Ditolak';

                $data->catatan_petugas = trim(
                    $validated['alasan']
                );

                // Surat ditolak tidak boleh membawa
                // identitas penerbitan sebelumnya.
                $data->nomor_surat = null;
                $data->nama_kepala_desa = null;

                $data->save();

                return 'Pengajuan berhasil ditolak dan alasan penolakan telah disimpan.';
            }

            /*
            |--------------------------------------------------------------------------
            | C. SELESAIKAN PENGAJUAN
            |--------------------------------------------------------------------------
            */

            if ($aksi === 'selesaikan') {

                if ($status !== 'Proses') {
                    throw ValidationException::withMessages([
                        'aksi' =>
                            'Surat harus dalam proses pengecekan terlebih dahulu.',
                    ]);
                }

                $nomorSurat = trim(
                    $validated['nomor_surat']
                );

                $namaKepalaDesa = trim(
                    $validated['nama_kepala_desa']
                );

                if ($nomorSurat === '') {
                    throw ValidationException::withMessages([
                        'nomor_surat' =>
                            'Nomor surat tidak boleh kosong.',
                    ]);
                }

                if ($namaKepalaDesa === '') {
                    throw ValidationException::withMessages([
                        'nama_kepala_desa' =>
                            'Nama kepala desa tidak boleh kosong.',
                    ]);
                }

                // Periksa nomor surat agar tidak dipakai
                // pada pengajuan selesai lainnya.
                $nomorSudahDipakai =
                    PengajuanSuratKependudukan::query()
                        ->where('nomor_surat', $nomorSurat)
                        ->where('id', '!=', $data->id)
                        ->where('status', 'Selesai')
                        ->exists();

                if ($nomorSudahDipakai) {
                    throw ValidationException::withMessages([
                        'nomor_surat' =>
                            'Nomor surat sudah digunakan pada pengajuan lain.',
                    ]);
                }

                $data->nomor_surat = $nomorSurat;
                $data->nama_kepala_desa = $namaKepalaDesa;

                $data->status = 'Selesai';
                $data->catatan_petugas = null;

                $data->save();

                return 'Surat berhasil diselesaikan. Nomor surat dan nama kepala desa telah disimpan.';
            }

            throw ValidationException::withMessages([
                'aksi' => 'Aksi tidak dikenali.',
            ]);
        });

        return back()->with('success', $pesan);
    }

    /*
    |--------------------------------------------------------------------------
    | 5. LIHAT LAMPIRAN DOKUMEN
    |--------------------------------------------------------------------------
    | Penyimpanan tetap privat.
    | Hanya admin login yang dapat mengakses.
    */

    public function lampiran(
        Request $request,
        PengajuanSuratKependudukan $pengajuan,
        string $kode
    ) {
        $this->admin($request);

        $dokumen = $pengajuan->dokumen ?? [];

        if (is_string($dokumen)) {
            $dokumen = json_decode($dokumen, true) ?: [];
        }

        abort_unless(is_array($dokumen), 404);

        $path = $dokumen[$kode] ?? null;

        abort_unless(
            is_string($path) && $path !== '',
            404,
            'Dokumen tidak ditemukan.'
        );

        $folder = 'kependudukan/' . $pengajuan->id . '/';

        abort_unless(
            str_starts_with($path, $folder)
                && !str_contains($path, '..')
                && !str_contains($path, '\\'),
            403,
            'Path dokumen tidak valid.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($path),
            404,
            'File tidak ditemukan.'
        );

        $fullPath = $disk->path($path);

        $mime = mime_content_type($fullPath);

        abort_unless(
            in_array($mime, [
                'image/jpeg',
                'image/png',
                'application/pdf',
            ], true),
            415,
            'Format dokumen tidak didukung.'
        );

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. PDF SURAT
    |--------------------------------------------------------------------------
    | Proses  = PDF pemeriksaan
    | Selesai = PDF dengan nomor surat dan kepala desa
    */

    public function pdf(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ) {
        $this->admin($request);

        abort_unless(
            in_array(
                $pengajuan->status,
                ['Proses', 'Selesai'],
                true
            ),
            403,
            'PDF belum tersedia pada status pengajuan ini.'
        );

        $pdf = Pdf::loadView(
            'surat.draft-kependudukan',
            [
                'pengajuan' => $pengajuan,
            ]
        )->setPaper('a4', 'portrait');

        $namaFile = 'SURAT-DESARA-' .
            $pengajuan->id . '.pdf';

        if ($request->query('download') === '1') {
            return $pdf->download($namaFile);
        }

        return $pdf->stream($namaFile);
    }
}