<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\BukuController;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'nama_buku',
        'kategori_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun_penerbitan',
        'jumlah_tersedia',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id', 'id');
    }
}
