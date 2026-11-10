<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSuratKependudukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class PerbaikanKependudukanController extends Controller
{
    public function edit(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ) {
        $this->pastikanBolehEdit($request, $pengajuan);

        $jenis = $pengajuan->jenis_surat;
        $layanan = config('desara_kependudukan.' . $jenis);

        abort_unless(is_array($layanan), 404);

        return view('layanan.form-kependudukan', compact(
            'jenis',
            'layanan',
            'pengajuan'
        ));
    }

    public function update(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ) {
        $this->pastikanBolehEdit($request, $pengajuan);

        $layanan = config(
            'desara_kependudukan.' . $pengajuan->jenis_surat
        );

        abort_unless(is_array($layanan), 404);

        $fields = $layanan['fields'] ?? [];
        $docs = $layanan['docs'] ?? $layanan['uploads'] ?? [];

        $rules = [
            'nama_pemohon' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'nomor_kk' => ['nullable', 'digits:16'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => [
                'required', 'date', 'before_or_equal:today'
            ],
            'jenis_kelamin' => [
                'required',
                Rule::in(['Laki-laki', 'Perempuan'])
            ],
            'alamat' => ['required', 'string', 'max:2000'],
            'rt_pemohon' => ['required', 'string', 'max:10'],
            'rw_pemohon' => ['required', 'string', 'max:10'],
            'dusun_pemohon' => ['required', 'string', 'max:255'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'status_perkawinan' => [
                'nullable',
                Rule::in([
                    'Belum kawin', 'Kawin',
                    'Cerai hidup', 'Cerai mati'
                ])
            ],
            'nomor_hp' => [
                'required',
                'string',
                'regex:/^08[0-9]{8,13}$/'
            ],
            'data' => ['sometimes', 'array'],
            'dokumen' => ['sometimes', 'array'],
        ];

        foreach ($fields as $index => $field) {
            if (!$this->kondisiTerpenuhi($field, $request)) {
                continue;
            }

            $key = $field['key'] ?? 'field_' . $index;
            $type = $field['type'] ?? 'text';
            $wajib = (bool) ($field['required'] ?? false);
            $nama = 'data.' . $key;

            if ($type === 'repeat') {
                $rules[$nama] = [
                    $wajib ? 'required' : 'nullable',
                    'array',
                    'min:1',
                    'max:100'
                ];

                foreach (($field['columns'] ?? []) as $kolom => $label) {
                    if ($kolom === 'nik') {
                        $opsional = str_contains(
                            strtolower((string) $label),
                            'jika ada'
                        );

                        $rules[$nama . '.*.' . $kolom] = [
                            $opsional ? 'nullable' : 'required',
                            'digits:16'
                        ];
                    } else {
                        $rules[$nama . '.*.' . $kolom] = [
                            'required', 'string', 'max:255'
                        ];
                    }
                }

                continue;
            }

            $aturan = [$wajib ? 'required' : 'nullable'];

            if ($type === 'date') {
                $aturan[] = 'date';
            } elseif ($type === 'number') {
                array_push($aturan, 'integer', 'min:0');
            } elseif ($type === 'select') {
                $aturan[] = 'string';
                $aturan[] = Rule::in($field['options'] ?? []);
            } elseif ($type === 'email') {
                array_push($aturan, 'email', 'max:255');
            } else {
                array_push(
                    $aturan,
                    'string',
                    $type === 'textarea' ? 'max:5000' : 'max:255'
                );
            }

            $rules[$nama] = $aturan;
        }

        foreach ($docs as $index => $doc) {
            if (!$this->kondisiTerpenuhi($doc, $request)) {
                continue;
            }

            $key = $doc['key'] ?? 'dokumen_' . $index;

            $rules['dokumen.' . $key] = [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ];

            $dokumenLama = $pengajuan->dokumen ?? [];

            if (
                ($doc['required'] ?? false)
                && empty($dokumenLama[$key])
                && !$request->hasFile('dokumen.' . $key)
            ) {
                $rules['dokumen.' . $key][0] = 'required';
            }
        }

        $validated = $request->validate($rules);

        $identitas = [
            'nama_pemohon',
            'nik',
            'nomor_kk',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'rt_pemohon',
            'rw_pemohon',
            'dusun_pemohon',
            'pekerjaan',
            'status_perkawinan',
            'nomor_hp'
        ];

        $dataPemohon = [];

        foreach ($identitas as $key) {
            $dataPemohon[$key] = $validated[$key] ?? null;
        }

        $dataPemohon['email_pemohon'] = $request->user()->email;

        $dataSurat = [];

        foreach ($fields as $index => $field) {
            if (!$this->kondisiTerpenuhi($field, $request)) {
                continue;
            }

            $key = $field['key'] ?? 'field_' . $index;

            $dataSurat[$key] = $validated['data'][$key] ?? null;
        }

        $fileBaru = [];

        try {
            DB::transaction(function () use (
                $request,
                $pengajuan,
                $docs,
                $dataPemohon,
                $dataSurat,
                &$fileBaru
            ) {
                $record = PengajuanSuratKependudukan::query()
                    ->whereKey($pengajuan->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->pastikanBolehEdit($request, $record);

                $lampiran = $record->dokumen ?? [];

                foreach ($docs as $index => $doc) {
                    if (!$this->kondisiTerpenuhi($doc, $request)) {
                        continue;
                    }

                    $key = $doc['key'] ?? 'dokumen_' . $index;

                    if (!$request->hasFile('dokumen.' . $key)) {
                        continue;
                    }

                    $path = $request
                        ->file('dokumen.' . $key)
                        ->store(
                            'kependudukan/' . $record->id,
                            'local'
                        );

                    if ($path === false) {
                        throw new \RuntimeException(
                            'Gagal menyimpan lampiran.'
                        );
                    }

                    $fileBaru[] = $path;
                    $lampiran[$key] = $path;
                }

                $record->update([
                    'data_pemohon' => $dataPemohon,
                    'data_surat' => $dataSurat,
                    'dokumen' => $lampiran,
                    'status' => 'Dalam Antrian'
                ]);
            });
        } catch (Throwable $e) {
            Storage::disk('local')->delete($fileBaru);
            throw $e;
        }

        return redirect('/warga/riwayat')->with(
            'success',
            'Pengajuan berhasil diperbaiki dan dikirim ulang.'
        );
    }

    private function pastikanBolehEdit(
        Request $request,
        PengajuanSuratKependudukan $pengajuan
    ): void {
        abort_unless(
            $request->user()
            && (int) $pengajuan->user_id ===
                (int) $request->user()->id,
            403
        );

        abort_unless(
            $pengajuan->status === 'Ditolak',
            403,
            'Hanya pengajuan yang ditolak dapat diperbaiki.'
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