<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BukuController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bukus",
     *     summary="Menampilkan semua data buku",
     *     tags={"Buku"},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil semua data buku"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan saat mengambil data buku"
     *     )
     * )
     */
    public function index()
    {
        // ...
    }

    /**
     * @OA\Get(
     *     path="/api/kategoris",
     *     summary="Menampilkan semua kategori buku",
     *     tags={"Buku"},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil semua kategori buku"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan saat mengambil data kategori"
     *     )
     * )
     */
    public function getKategori()
    {
        // ...
    }

    /**
     * @OA\Post(
     *     path="/api/bukus",
     *     summary="Menyimpan data buku baru",
     *     tags={"Buku"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"kategori_id", "nama_buku", "judul", "penulis", "penerbit", "jumlah_tersedia"},
     *             @OA\Property(property="kategori_id", type="integer", example=1),
     *             @OA\Property(property="nama_buku", type="string", example="Dasar Pemrograman"),
     *             @OA\Property(property="judul", type="string", example="Belajar Laravel 10"),
     *             @OA\Property(property="penulis", type="string", example="Jane Doe"),
     *             @OA\Property(property="penerbit", type="string", example="Erlangga"),
     *             @OA\Property(property="tahun_penerbitan", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="jumlah_tersedia", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Buku berhasil ditambahkan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // ...
    }

    /**
     * @OA\Get(
     *     path="/api/bukus/{id}",
     *     summary="Menampilkan detail buku berdasarkan ID",
     *     tags={"Buku"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID buku",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail buku ditemukan"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Buku tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan"
     *     )
     * )
     */
    public function show($id)
    {
        // ...
    }

    /**
     * @OA\Put(
     *     path="/api/bukus/{id}",
     *     summary="Memperbarui data buku",
     *     tags={"Buku"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID buku",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="kategori_id", type="integer", example=1),
     *             @OA\Property(property="nama_buku", type="string", example="Pemrograman Web"),
     *             @OA\Property(property="judul", type="string", example="Mastering Laravel"),
     *             @OA\Property(property="penulis", type="string", example="John Doe"),
     *             @OA\Property(property="penerbit", type="string", example="Gramedia"),
     *             @OA\Property(property="tahun_penerbitan", type="string", format="date", example="2022-01-01"),
     *             @OA\Property(property="jumlah_tersedia", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Buku berhasil diperbarui"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Buku tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // ...
    }

    /**
     * @OA\Delete(
     *     path="/api/bukus/{id}",
     *     summary="Menghapus data buku",
     *     tags={"Buku"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID buku",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Buku berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Buku tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan saat menghapus buku"
     *     )
     * )
     */
    public function destroy($id)
    {
        // ...
    }
}
