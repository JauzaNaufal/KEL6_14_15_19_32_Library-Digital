<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriBukuController;

Route::apiResource('anggotas', AnggotaController::class);
Route::apiResource('peminjamen', PeminjamanController::class);
Route::apiResource('petugas', PetugasController::class);
Route::apiResource('bukus', BukuController::class);
Route::apiResource('kategori', KategoriBukuController::class);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
