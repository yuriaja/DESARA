<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSuratKependudukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class PengajuanKependudukanController extends Controller
{
    public function store(Request $request, string $jenis)
    {
        // Ambil jenis surat dari konfigurasi DESARA.
        $layanan = config('desara_kependudukan.' . $jenis);

        abort_if(
            !is_array($layanan),
            404,
            'Jenis surat tidak ditemukan.'
        );

        $fields = $layanan['fields'] ?? [];
        $docs = $layanan['docs'] ?? $layanan['uploads'] ?? [];

        // Validasi identitas pemohon.
        $rules = [
            'nama_pemohon' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'nomor_kk' => ['nullable', 'digits:16'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => [
                'required',
                Rule::in(['Laki-laki', 'Perempuan']),
            ],
            'alamat' => ['required', 'string', 'max:2000'],
            'rt_pemohon' => ['required', 'string', 'max:10'],
            'rw_pemohon' => ['required', 'string', 'max:10'],
            'dusun_pemohon' => ['required', 'string', 'max:255'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'status_perkawinan' => [
                'nullable',
                Rule::in([
                    'Belum kawin',
                    'Kawin',
                    'Cerai hidup',
                    'Cerai mati',
                ]),
            ],
            'nomor_hp' => [
                'required',
                'string',
                'regex:/^08[0-9]{8,13}$/',
            ],
            'data' => ['sometimes', 'array'],
            'dokumen' => ['sometimes', 'array'],
        ];

        // Validasi pertanyaan khusus sesuai jenis surat.
        foreach ($fields as $index => $field) {

            $key = $field['key'] ?? 'field_' . $index;
            $type = $field['type'] ?? 'text';
            $type = is_string($type) ? $type : 'text';

            $wajib = (bool) ($field['required'] ?? false);

            if (!$this->kondisiTerpenuhi($field, $request)) {
                continue;
            }

            $namaInput = 'data.' . $key;
            $aturan = [$wajib ? 'required' : 'nullable'];

            if ($type === 'repeat') {

                $rules[$namaInput] = [
                    $wajib ? 'required' : 'nullable',
                    'array',
                    'min:1',
                    'max:100',
                ];

                foreach (($field['columns'] ?? []) as $kolom => $label) {

                    $namaKolom = $namaInput . '.*.' . $kolom;

                    if ($kolom === 'nik') {
                        // NIK boleh kosong hanya bila label menyatakan "jika ada".
                        $opsional = str_contains(
                            strtolower((string) $label),
                            'jika ada'
                        );

                        $rules[$namaKolom] = $opsional
                            ? ['nullable', 'digits:16']
                            : ['required', 'digits:16'];
                    } else {
                        $rules[$namaKolom] = [
                            'required',
                            'string',
                            'max:255',
                        ];
                    }
                }

                continue;
            }

            if ($type === 'date') {
                $aturan[] = 'date';

            } elseif ($type === 'number') {
                $aturan[] = 'integer';
                $aturan[] = 'min:0';

            } elseif ($type === 'select') {
                $aturan[] = 'string';
                $aturan[] = Rule::in($field['options'] ?? []);

            } elseif ($type === 'email') {
                $aturan[] = 'email';
                $aturan[] = 'max:255';

            } else {
                $aturan[] = 'string';
                $aturan[] = $type === 'textarea'
                    ? 'max:5000'
                    : 'max:255';
            }

            $rules[$namaInput] = $aturan;
        }

        // Validasi dokumen berdasarkan konfigurasi.
        foreach ($docs as $index => $doc) {

            $key = $doc['key'] ?? 'dokumen_' . $index;

            if (!$this->kondisiTerpenuhi($doc, $request)) {
                continue;
            }

            $wajib = (bool) ($doc['required'] ?? false);

            $rules['dokumen.' . $key] = [
                $wajib ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ];
        }

        $validated = Validator::make(
            $request->all(),
            $rules,
            [
                'required' => ':attribute wajib diisi.',
                'digits' => ':attribute harus :digits digit.',
                'mimes' => 'Dokumen harus JPG, PNG, atau PDF.',
                'max' => ':attribute melebihi batas yang diizinkan.',
            ]
        )->validate();

        // Data identitas dari akun, bukan input yang dapat diubah di browser.
        $dataPemohon = [
            'nama_pemohon' => $validated['nama_pemohon'],
            'email_pemohon' => $request->user()->email,
            'nik' => $validated['nik'],
            'nomor_kk' => $validated['nomor_kk'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'rt_pemohon' => $validated['rt_pemohon'],
            'rw_pemohon' => $validated['rw_pemohon'],
            'dusun_pemohon' => $validated['dusun_pemohon'],
            'pekerjaan' => $validated['pekerjaan'] ?? null,
            'status_perkawinan' => $validated['status_perkawinan'] ?? null,
            'nomor_hp' => $validated['nomor_hp'],
        ];

        // Simpan hanya jawaban field yang memang dikonfigurasi.
        $dataSurat = [];

        foreach ($fields as $index => $field) {

            if (!$this->kondisiTerpenuhi($field, $request)) {
                continue;
            }

            $key = $field['key'] ?? 'field_' . $index;

            $dataSurat[$key] = $validated['data'][$key] ?? null;
        }

        $pengajuan = null;

        DB::beginTransaction();

        try {
            $pengajuan = PengajuanSuratKependudukan::create([
                'user_id' => $request->user()->id,
                'jenis_surat' => $jenis,
                'data_pemohon' => $dataPemohon,
                'data_surat' => $dataSurat,
                'dokumen' => [],
                'status' => 'Dalam Antrian',
            ]);

            $lampiran = [];

            foreach ($docs as $index => $doc) {

                if (!$this->kondisiTerpenuhi($doc, $request)) {
                    continue;
                }

                $key = $doc['key'] ?? 'dokumen_' . $index;

                if (!$request->hasFile('dokumen.' . $key)) {
                    continue;
                }

                $file = $request->file('dokumen.' . $key);

                // Disk local: simpan lampiran secara privat.
                $path = $file->store(
                    'kependudukan/' . $pengajuan->id,
                    'local'
                );

                if ($path === false) {
                    throw new \RuntimeException(
                        'Gagal menyimpan dokumen pengajuan.'
                    );
                }

                $lampiran[$key] = $path;
            }

            $pengajuan->update([
                'dokumen' => $lampiran,
            ]);

            DB::commit();

        } catch (Throwable $e) {

            DB::rollBack();

            if ($pengajuan !== null) {
                Storage::disk('local')->deleteDirectory(
                    'kependudukan/' . $pengajuan->id
                );
            }

            Log::error('Gagal menyimpan pengajuan kependudukan', [
                'jenis_surat' => $jenis,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput($request->except('dokumen'))
                ->withErrors([
                    'pengajuan' =>
                        'Pengajuan gagal disimpan. Silakan coba lagi.',
                ]);
        }

        return redirect()
            ->route('warga.form-kependudukan', [
                'jenis' => $jenis,
            ])
            ->with(
                'success',
                'Pengajuan berhasil dikirim. silahkan check secara berkala untuk melihat status pengajuan Anda di menu "Riwayat Pengajuan".'
            );
    }

    private function kondisiTerpenuhi(
        array $item,
        Request $request
    ): bool {
        $when = $item['when'] ?? null;

        if (!$when) {
            return true;
        }

        $key = $when['key'] ?? null;
        $pilihan = $when['in'] ?? [];

        if (!$key || !is_array($pilihan)) {
            return false;
        }

        $jawaban = $request->input('data.' . $key);

        return is_scalar($jawaban)
            && in_array($jawaban, $pilihan, true);
    }
}