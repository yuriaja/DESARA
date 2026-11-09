<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSuratKependudukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RiwayatKependudukanController extends Controller
{
    private const STATUS = [
        'Dalam Antrian',
        'Proses',
        'Selesai',
    ];

    // RIWAYAT PENGAJUAN MILIK WARGA
    public function index(Request $request)
    {
        $items = PengajuanSuratKependudukan::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('layanan.history', compact('items'));
    }

    // DAFTAR SELURUH PENGAJUAN UNTUK ADMIN
    public function adminIndex(Request $request)
    {
        $this->pastikanAdmin($request);

        $query = PengajuanSuratKependudukan::query()
            ->with('user');

        // Filter status.
        $status = $request->query('status');

        if (in_array($status, self::STATUS, true)) {
            $query->where('status', $status);
        }

        // Pencarian ID dan jenis surat.
        $cari = trim((string) $request->query('q', ''));

        if ($cari !== '') {
            $query->where(function ($q) use ($cari) {

                $q->where('jenis_surat', 'like', '%' . $cari . '%');

                if (ctype_digit($cari)) {
                    $q->orWhere('id', (int) $cari);
                }
            });
        }

        $pengajuans = $query
            ->latest('created_at')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'layanan.admin-kependudukan',
            compact('pengajuans')
        );
    }

    // ADMIN MENGUBAH STATUS PENGAJUAN
    public function updateStatus(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ) {
        $this->pastikanAdmin($request);

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(self::STATUS),
            ],
        ]);

        DB::transaction(function () use ($pengajuan, $validated) {

            $data = PengajuanSuratKependudukan::query()
                ->whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $data->update([
                'status' => $validated['status'],
            ]);
        });

        return back()->with(
            'success',
            'Status pengajuan #' . $pengajuan->id .
            ' berhasil diubah menjadi ' . $validated['status'] . '.'
        );
    }

    // HANYA USER DENGAN ROLE ADMIN
    private function pastikanAdmin(Request $request): void
    {
        abort_unless(
            $request->user() !== null &&
            $request->user()->role === 'admin',
            403,
            'Hanya admin yang boleh mengelola status pengajuan.'
        );
    }
}