<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'nama_buku',
        'judul',
        'penulis',
        'penerbit',
        'tahun_penerbitan',
        'jumlah_tersedia',
    ];
}
