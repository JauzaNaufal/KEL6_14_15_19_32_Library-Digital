<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\KategoriBukuController;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class KategoriBuku extends Model
{
    use HasFactory;

    protected $table = 'kategori_bukus';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];
}
