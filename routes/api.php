<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriBukuController;



Route::prefix('auth')->group(function () {
    Route::post('register', [PetugasController::class, 'register']);
    Route::post('login', [PetugasController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [PetugasController::class, 'logout']);
        Route::get('profile', [PetugasController::class, 'profile']);
        Route::put('update-profile', [PetugasController::class, 'updateProfile']);
        Route::put('change-password', [PetugasController::class, 'changePassword']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('buku/search', [BukuController::class, 'search']);
    Route::get('buku/kategori-list', [BukuController::class, 'getKategori']);
    Route::get('buku/kategori/{id}', [BukuController::class, 'getBooksByCategory']);
    Route::apiResource('anggotas', AnggotaController::class);
    Route::apiResource('peminjaman', PeminjamanController::class);
    Route::apiResource('petugas', PetugasController::class);
    Route::apiResource('buku', BukuController::class);
    Route::apiResource('kategori', KategoriBukuController::class);
});
