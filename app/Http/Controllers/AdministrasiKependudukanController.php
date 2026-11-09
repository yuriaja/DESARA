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
    | Menampilkan surat yang masih dalam antrean.
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
    | Tab proses dan selesai.
    | Surat dikelompokkan berdasarkan tanggal masuk.
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
                'tanggal' => [
                    'date_format:Y-m-d',
                ],
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

        return view('layanan.rekap-administrasi', compact(
            'tab',
            'tanggal',
            'kelompokTanggal',
            'jumlahProses',
            'jumlahSelesai'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | 3. DETAIL PENGAJUAN
    |--------------------------------------------------------------------------
    | Menampilkan data pemohon, data surat, dan lampiran.
    */

    public function detail(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ) {
        $this->admin($request);

        return view('layanan.detail-administrasi', compact(
            'pengajuan'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | 4. KEPUTUSAN ADMIN
    |--------------------------------------------------------------------------
    | terima      = Dalam Antrian -> Proses
    | tolak       = Dalam Antrian / Proses -> Ditolak
    | selesaikan  = Proses -> Selesai
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
        ]);

        DB::transaction(function () use (
            $pengajuan,
            $validated
        ) {
            $data = PengajuanSuratKependudukan::query()
                ->whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $status = $data->status;
            $aksi = $validated['aksi'];

            // MENYETUJUI PENGAJUAN AWAL
            if ($aksi === 'terima') {

                if (!in_array(
                    $status,
                    ['Dalam Antrian', 'Diajukan'],
                    true
                )) {
                    throw ValidationException::withMessages([
                        'aksi' => 'Surat ini sudah tidak dalam antrean.',
                    ]);
                }

                $data->status = 'Proses';
                $data->catatan_petugas = null;
            }

            // MENOLAK PENGAJUAN
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
                        'aksi' => 'Surat tidak dapat ditolak pada status ini.',
                    ]);
                }

                $data->status = 'Ditolak';

                $data->catatan_petugas = trim(
                    $validated['alasan']
                );
            }

            // MENYELESAIKAN PENGAJUAN
            if ($aksi === 'selesaikan') {

                if ($status !== 'Proses') {
                    throw ValidationException::withMessages([
                        'aksi' => 'Surat harus dalam proses pengecekan terlebih dahulu.',
                    ]);
                }

                $data->status = 'Selesai';
                $data->catatan_petugas = null;
            }

            $data->save();
        });

        return back()->with(
            'success',
            'Status pengajuan #' . $pengajuan->id .
            ' berhasil diperbarui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 5. LIHAT LAMPIRAN DOKUMEN
    |--------------------------------------------------------------------------
    | Menampilkan JPG, PNG, atau PDF di browser.
    | Akses hanya diberikan kepada admin yang login.
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

    $path = $dokumen[$kode] ?? null;

    abort_unless(is_string($path) && $path !== '', 404);

    $folder = 'kependudukan/' . $pengajuan->id . '/';

    abort_unless(
        str_starts_with($path, $folder)
        && !str_contains($path, '..')
        && !str_contains($path, '\\'),
        403
    );

    $disk = Storage::disk('local');

    abort_unless($disk->exists($path), 404);

    $fullPath = $disk->path($path);
    $mime = mime_content_type($fullPath);

    abort_unless(
        in_array($mime, [
            'image/jpeg',
            'image/png',
            'application/pdf'
        ], true),
        415
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
    | Default: buka PDF di browser.
    | ?download=1: unduh PDF.
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

        // Download jika terdapat ?download=1.
        if ($request->query('download') === '1') {
            return $pdf->download($namaFile);
        }

        // Tampilkan PDF di browser.
        return $pdf->stream($namaFile);
    }
}