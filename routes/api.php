<?php

use App\Http\Controllers\Api\SuratApiController;
use App\Http\Controllers\SuratController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| File ini dimuat dengan prefix otomatis "/api" (lihat bootstrap/app.php),
| jadi URL akhirnya tetap /api/surat/keterangan.
| Group "api" tidak memakai session/CSRF, sehingga cocok untuk frontend.
*/

Route::post('/surat/keterangan', [SuratController::class, 'apiStoreKeterangan']);
Route::post('/surat/kematian', [SuratApiController::class, 'storeKematian']);