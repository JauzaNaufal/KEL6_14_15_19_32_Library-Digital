<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Validation\ValidationException;


class PeminjamanController extends Controller
{

    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->get();
        return response()->json($peminjaman);
    }


    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'buku_id' => 'required|exists:bukus,id',
            'petugas_id' => 'required|exists:petugas,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan'
        ]);

        $buku = Buku::find($request->buku_id);

        if ($buku->jumlah_tersedia < 1) {
            return response()->json(['message' => 'Buku tidak tersedia untuk dipinjam'], 400);
        }

        $peminjaman = Peminjaman::create($request->all());


        $buku->decrement('jumlah_tersedia');

        return response()->json([
            'message' => 'Peminjaman berhasil ditambahkan!',
            'peminjaman' => $peminjaman
        ], 201);
    }


}