<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AnggotaController;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';
    protected $fillable = [
        'nama',
        'nomor_telepon',
        'email',
        'tanggal_bergabung'
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjamen::class, 'anggota_id');
    }
}
