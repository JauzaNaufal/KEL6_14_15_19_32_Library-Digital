<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriBukuController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Buku;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('kategori', KategoriBukuController::class);
Route::resource('buku', BukuController::class);
Route::resource('petugas', PetugasController::class);
