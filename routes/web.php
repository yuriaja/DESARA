<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-surat', [SuratController::class, 'generateTest']);
Route::get('/surat/keterangan', [SuratController::class, 'create']);
Route::post('/surat/keterangan', [SuratController::class, 'store']);
Route::get('/surat/{id}/pdf', [SuratController::class, 'showPdf']);