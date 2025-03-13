<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriBukuController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PetugasController;


Route::get('/', function () {
    return view('welcome');
});

