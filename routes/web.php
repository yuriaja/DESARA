<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuratController;
use App\Http\Controllers\LayananWargaController;
use App\Http\Controllers\PratinjauKependudukanController;
use App\Http\Controllers\PengajuanKependudukanController;
use App\Http\Controllers\RiwayatKependudukanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AdministrasiKependudukanController;
use App\Http\Controllers\PerbaikanKependudukanController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('layanan.login');
});


/*
|--------------------------------------------------------------------------
| 2. MODUL SURAT
|--------------------------------------------------------------------------
| Route pembuatan surat lama tetap dipertahankan.
*/

Route::controller(SuratController::class)->group(function () {

    Route::get('/test-surat', 'generateTest');

    Route::get('/surat/keterangan', 'create');

    Route::post('/surat/keterangan', 'store');

    Route::get('/surat/{id}/pdf', 'showPdf');

});


/*
|--------------------------------------------------------------------------
| 3. AUTENTIKASI WARGA
|--------------------------------------------------------------------------
*/

Route::controller(LayananWargaController::class)->group(function () {

    Route::get('/masuk-warga', 'loginPage')
        ->name('layanan.login');

    Route::post('/masuk-warga', 'login')
        ->middleware('throttle:6,1')
        ->name('layanan.login.post');

    Route::get('/daftar-warga', 'registerPage')
        ->name('layanan.register');

    Route::post('/daftar-warga', 'register')
        ->middleware('throttle:5,1')
        ->name('layanan.register.post');

    Route::post('/keluar-warga', 'logout')
        ->middleware('auth')
        ->name('layanan.logout');

});


/*
|--------------------------------------------------------------------------
| 4. MODUL WARGA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 4.1 DASHBOARD DAN KATEGORI
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/warga',
        [LayananWargaController::class, 'home']
    )->name('warga.home');

    Route::get(
        '/warga/kategori/{category}',
        [LayananWargaController::class, 'category']
    )->name('warga.category');


    /*
    |--------------------------------------------------------------------------
    | 4.2 LAYANAN ADMINISTRASI UMUM
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/warga/layanan/{service}',
        [LayananWargaController::class, 'apply']
    )->name('warga.apply');

    Route::post(
        '/warga/layanan/{service}',
        [LayananWargaController::class, 'submit']
    )
        ->middleware('throttle:5,1')
        ->name('warga.submit');


    /*
    |--------------------------------------------------------------------------
    | 4.3 FORMULIR KEPENDUDUKAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/warga/form-kependudukan/{jenis}',
        function (string $jenis) {

            $semuaLayanan = config(
                'desara_kependudukan',
                []
            );

            $layanan = $semuaLayanan[$jenis] ?? null;

            abort_if(
                !$layanan,
                404,
                'Jenis surat tidak ditemukan.'
            );

            return view('layanan.form-kependudukan', [
                'layanan' => $layanan,
                'jenis' => $jenis,
            ]);
        }
    )->name('warga.form-kependudukan');


    /*
    |--------------------------------------------------------------------------
    | 4.4 SIMPAN PENGAJUAN KEPENDUDUKAN
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/warga/form-kependudukan/{jenis}',
        [PengajuanKependudukanController::class, 'store']
    )
        ->middleware('throttle:5,1')
        ->name('warga.kependudukan.store');


    /*
    |--------------------------------------------------------------------------
    | 4.5 PRATINJAU KEPENDUDUKAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/warga/pratinjau-kependudukan/{service}',
        [PratinjauKependudukanController::class, 'show']
    )->name('warga.pratinjau-kependudukan');


    /*
    |--------------------------------------------------------------------------
    | 4.6 RIWAYAT PENGAJUAN
    |--------------------------------------------------------------------------
    | Menggunakan tabel pengajuan_surat_kependudukans.
    */

    Route::get(
        '/warga/riwayat',
        [RiwayatKependudukanController::class, 'index']
    )->name('warga.history');


    /*
    |--------------------------------------------------------------------------
    | 4.7 DETAIL DAN PERBAIKAN PENGAJUAN LAMA
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pengajuan/{p}',
        [LayananWargaController::class, 'detail']
    )->name('warga.detail');

    Route::post(
        '/pengajuan/{p}/perbaiki',
        [LayananWargaController::class, 'repair']
    )->name('warga.repair');

    Route::get(
        '/lampiran/{file}',
        [LayananWargaController::class, 'attachment']
    )->name('warga.attachment');

    Route::get(
        '/hasil/{p}',
        [LayananWargaController::class, 'hasil']
    )->name('warga.hasil');


    /*
    |--------------------------------------------------------------------------
    | 4.8 PROFIL DAN PENGATURAN AKUN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/warga/profil',
        [ProfilController::class, 'index']
    )->name('warga.profil');

    Route::put(
        '/warga/profil',
        [ProfilController::class, 'update']
    )->name('warga.profil.update');

    Route::put(
        '/warga/profil/password',
        [ProfilController::class, 'updatePassword']
    )->name('warga.profil.password');

    Route::post(
        '/warga/profil/nanti-saja',
        [ProfilController::class, 'nantiSaja']
    )->name('warga.profil.nanti');

});


/*
|--------------------------------------------------------------------------
| 5. MODUL PETUGAS / ADMIN
|--------------------------------------------------------------------------
| Semua route ini memakai login.
| Controller AdministrasiKependudukanController memeriksa role admin.
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 5.1 PENGAJUAN WARGA
    |--------------------------------------------------------------------------
    | Menampilkan pengajuan yang masih Dalam Antrian.
    */

    Route::get(
        '/petugas/pengajuan',
        [AdministrasiKependudukanController::class, 'index']
    )->name('petugas.home');


    /*
    |--------------------------------------------------------------------------
    | 5.2 REKAP ADMINISTRASI
    |--------------------------------------------------------------------------
    | Dua tab:
    | - Surat dalam Proses Pengecekan
    | - Surat Telah Selesai
    */

    Route::get(
        '/petugas/rekap-administrasi',
        [AdministrasiKependudukanController::class, 'rekap']
    )->name('petugas.rekap');


    /*
    |--------------------------------------------------------------------------
    | 5.3 DETAIL ADMINISTRASI
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/petugas/administrasi/{pengajuan}',
        [AdministrasiKependudukanController::class, 'detail']
    )->name('petugas.administrasi.detail');


    /*
    |--------------------------------------------------------------------------
    | 5.4 SETUJUI, TOLAK, SELESAIKAN
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/petugas/administrasi/{pengajuan}/keputusan',
        [AdministrasiKependudukanController::class, 'keputusan']
    )->name('petugas.administrasi.keputusan');


    /*
    |--------------------------------------------------------------------------
    | 5.5 DOWNLOAD LAMPIRAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/petugas/administrasi/{pengajuan}/lampiran/{kode}',
        [AdministrasiKependudukanController::class, 'lampiran']
    )->name('petugas.administrasi.lampiran');


    /*
    |--------------------------------------------------------------------------
    | 5.6 DOWNLOAD PDF SURAT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/petugas/administrasi/{pengajuan}/pdf',
        [AdministrasiKependudukanController::class, 'pdf']
    )->name('petugas.administrasi.pdf');


    /*
    |--------------------------------------------------------------------------
    | 5.7 MODUL PETUGAS LAMA
    |--------------------------------------------------------------------------
    | Dipertahankan untuk kompatibilitas modul lama.
    */

    Route::post(
        '/petugas/pengajuan/{p}',
        [LayananWargaController::class, 'update']
    )->name('petugas.update');


    /*
    |--------------------------------------------------------------------------
    | 5.8 KELOLA KEPENDUDUKAN LAMA
    |--------------------------------------------------------------------------
    | Halaman lama tetap tersedia jika masih digunakan.
    */

    Route::get(
        '/admin/kependudukan',
        [RiwayatKependudukanController::class, 'adminIndex']
    )->name('admin.kependudukan.index');

    Route::get(
        '/warga/pengajuan/{pengajuan}/perbaiki',
        [PerbaikanKependudukanController::class, 'edit']
    )->name('warga.kependudukan.edit');

    Route::put(
        '/warga/pengajuan/{pengajuan}/perbaiki',
        [PerbaikanKependudukanController::class, 'update']
    )->name('warga.kependudukan.update');

});


/*
|--------------------------------------------------------------------------
| 6. TEMPAT MENAMBAHKAN MODUL BARU
|--------------------------------------------------------------------------
| Tambahkan route administrasi kategori lainnya di bawah ini.
|--------------------------------------------------------------------------
*/
