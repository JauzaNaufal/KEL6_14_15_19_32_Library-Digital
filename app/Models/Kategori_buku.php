<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori_buku extends Model
{
    use HasFactory;

    protected $table = 'kategori_bukus';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];
}
