<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'nama_petugas',
        'posisi',
        'nomor_telepon',
        'email',
    ];
}
