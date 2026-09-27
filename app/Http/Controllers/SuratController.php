<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Surat;
use App\Models\JenisSurat;

class SuratController extends Controller
{
    public function create()
    {
        return view('surat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nik_pemohon' => 'required|digits:16',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'pekerjaan' => 'nullable|string',
            'agama' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'alamat_pemohon' => 'required|string',
            'keterangan' => 'nullable|string',
            'keperluan' => 'nullable|string',
            'masa_mulai' => 'nullable|date',
            'masa_selesai' => 'nullable|date',
        ]);

        $jenisSurat = JenisSurat::where('kode_surat', 'KET')->first();

        // Generate nomor urut otomatis (ambil surat terakhir jenis ini, tambah 1)
        $lastSurat = Surat::where('jenis_surat_id', $jenisSurat->id)->latest('id')->first();
        $nomorUrut = $lastSurat ? ((int) preg_replace('/[^0-9]/', '', explode('/', $lastSurat->nomor_surat)[1] ?? 0) + 1) : 1;
        $nomorSurat = "474.4/{$nomorUrut}/403.408.4/2026";

        $surat = Surat::create([
            'user_id' => 1, // sementara hardcode user id 1, nanti diganti auth()->id()
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => $nomorSurat,
            'nama_pemohon' => $validated['nama_pemohon'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'pekerjaan' => $validated['pekerjaan'],
            'agama' => $validated['agama'],
            'status_perkawinan' => $validated['status_perkawinan'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat_pemohon' => $validated['alamat_pemohon'],
            'data_tambahan' => [
                'keterangan' => $validated['keterangan'],
                'keperluan' => $validated['keperluan'],
                'masa_mulai' => $validated['masa_mulai'],
                'masa_selesai' => $validated['masa_selesai'],
            ],
            'tanggal_terbit' => now(),
            'status_arsip' => 'aktif',
        ]);

        return redirect("/surat/{$surat->id}/pdf");
    }

    public function showPdf($id)
    {
        $surat = Surat::findOrFail($id);

        $data = [
            'nomor_surat' => $surat->nomor_surat,
            'nama_pemohon' => $surat->nama_pemohon,
            'nik_pemohon' => $surat->nik_pemohon,
            'tempat_lahir' => $surat->tempat_lahir,
            'tanggal_lahir' => $surat->tanggal_lahir ? $surat->tanggal_lahir->translatedFormat('d F Y') : '-',
            'pekerjaan' => $surat->pekerjaan,
            'agama' => $surat->agama,
            'status_perkawinan' => $surat->status_perkawinan,
            'jenis_kelamin' => $surat->jenis_kelamin,
            'alamat_pemohon' => $surat->alamat_pemohon,
            'keterangan' => $surat->data_tambahan['keterangan'] ?? '-',
            'keperluan' => $surat->data_tambahan['keperluan'] ?? '-',
            'masa_mulai' => $surat->data_tambahan['masa_mulai'] ?? '-',
            'masa_selesai' => $surat->data_tambahan['masa_selesai'] ?? '-',
            'tanggal_terbit' => $surat->tanggal_terbit->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('surat.keterangan', $data);

        return $pdf->stream("surat-keterangan-{$surat->nama_pemohon}.pdf");
    }

    public function generateTest()
    {
        $data = [
            'nomor_surat' => '474.4/999/403.408.4/2026',
            'nama_pemohon' => 'Budi Santoso',
            'nik_pemohon' => '3520081234560001',
            'tempat_lahir' => 'Magetan',
            'tanggal_lahir' => '15 Januari 1995',
            'pekerjaan' => 'Wiraswasta',
            'agama' => 'Islam',
            'status_perkawinan' => 'Menikah',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_pemohon' => 'Dsn. Waru Kulon RT.001 RW.002, Ds. Milangasri, Kec. Panekan, Kab. Magetan',
            'keterangan' => 'Yang bersangkutan benar-benar penduduk Desa Milangasri dan memiliki usaha jualan sembako.',
            'keperluan' => 'Persyaratan pengajuan pinjaman di Bank',
            'masa_mulai' => '25-09-2026',
            'masa_selesai' => '25-10-2026',
            'tanggal_terbit' => '25 September 2026',
        ];

        $pdf = Pdf::loadView('surat.keterangan', $data);

        return $pdf->stream('test-surat-keterangan.pdf');
    }
}