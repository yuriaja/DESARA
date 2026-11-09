<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    // Menampilkan halaman profil.
    public function index(Request $request)
    {
        $user = $request->user();

        $kolomBelumLengkap = $this->dataBelumLengkap($user);

        $profilLengkap = count($kolomBelumLengkap) === 0;

        return view('layanan.profil', compact(
            'user',
            'kolomBelumLengkap',
            'profilLengkap'
        ));
    }

    // Menyimpan identitas yang dilengkapi warga.
    public function update(Request $request)
    {
        $validated = $request->validate([
            'tempat_lahir' => [
                'required',
                'string',
                'max:255',
            ],
            'tanggal_lahir' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'jenis_kelamin' => [
                'required',
                Rule::in(['Laki-laki', 'Perempuan']),
            ],
            'telepon' => [
                'required',
                'string',
                'regex:/^08[0-9]{8,13}$/',
            ],
        ], [
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before_or_equal' =>
                'Tanggal lahir tidak boleh melebihi hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'telepon.required' => 'Nomor HP wajib diisi.',
            'telepon.regex' =>
                'Nomor HP harus diawali 08 dan berisi 10–15 digit.',
        ]);

        $user = $request->user();

        $user->tempat_lahir = $validated['tempat_lahir'];
        $user->tanggal_lahir = $validated['tanggal_lahir'];
        $user->jenis_kelamin = $validated['jenis_kelamin'];
        $user->telepon = $validated['telepon'];

        $user->save();

        // Hentikan pengingat pada sesi ini setelah profil lengkap.
        $request->session()->forget('profil_diingatkan');

        return redirect()
            ->route('warga.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // Mengubah password akun.
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => [
                'required',
                'string',
            ],
            'password_baru' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:password_lama',
            ],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' =>
                'Password baru minimal 8 karakter.',
            'password_baru.confirmed' =>
                'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();

        if (!Hash::check(
            $validated['password_lama'],
            $user->password
        )) {
            throw ValidationException::withMessages([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->password = Hash::make(
            $validated['password_baru']
        );

        $user->save();

        $request->session()->regenerate();

        return redirect()
            ->route('warga.profil')
            ->with('success', 'Password berhasil diubah.');
    }

    // Menyembunyikan pengingat sementara.
    public function nantiSaja(Request $request)
    {
        $request->session()->put('profil_diingatkan', true);

        return back();
    }

    // Mengecek data yang belum dilengkapi.
    private function dataBelumLengkap($user): array
    {
        $data = [
            'Nama lengkap' => $user->name,
            'NIK' => $user->nik,
            'Email' => $user->email,
            'Tempat lahir' => $user->tempat_lahir,
            'Tanggal lahir' => $user->tanggal_lahir,
            'Jenis kelamin' => $user->jenis_kelamin,
            'Nomor HP' => $user->telepon,
        ];

        $belumLengkap = [];

        foreach ($data as $label => $nilai) {
            if (
                $nilai === null ||
                trim((string) $nilai) === ''
            ) {
                $belumLengkap[] = $label;
            }
        }

        return $belumLengkap;
    }
}