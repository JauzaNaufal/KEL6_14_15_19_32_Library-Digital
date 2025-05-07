<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Info(
 *     title="Buku API",
 *     version="1.0.0",
 *     description="API untuk manajemen buku",
 *     @OA\Contact(
 *         email="contact@example.com",
 *         name="API Support"
 *     )
 * )
 */

class BukuController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/buku",
     *     tags={"Buku"},
     *     summary="Menampilkan semua buku",
     *     operationId="bukuIndex",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data buku",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BukuSchema")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan saat mengambil data buku",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat mengambil data buku"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $buku = Buku::with('kategori')->get();
            return response()->json($buku);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/kategori",
     *     tags={"Buku"},
     *     summary="Menampilkan semua kategori buku",
     *     operationId="kategoriIndex",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data kategori",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/KategoriBukuSchema")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan saat mengambil data kategori",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat mengambil data kategori"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function getKategori()
    {
        try {
            $kategori = KategoriBuku::all();
            return response()->json($kategori);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/buku",
     *     tags={"Buku"},
     *     summary="Menambahkan buku baru",
     *     operationId="bukuStore",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data buku yang akan ditambahkan",
     *         @OA\JsonContent(
     *             required={"kategori_id", "nama_buku", "judul", "penulis", "penerbit", "jumlah_tersedia"},
     *             @OA\Property(property="kategori_id", type="integer", example=1),
     *             @OA\Property(property="nama_buku", type="string", example="Harry Potter"),
     *             @OA\Property(property="judul", type="string", example="Harry Potter and the Philosopher's Stone"),
     *             @OA\Property(property="penulis", type="string", example="J.K. Rowling"),
     *             @OA\Property(property="penerbit", type="string", example="Gramedia"),
     *             @OA\Property(property="tahun_penerbitan", type="string", format="date", example="2001-01-01"),
     *             @OA\Property(property="jumlah_tersedia", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Buku berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Buku berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BukuSchema"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validasi gagal"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"nama_buku": {"Nama buku harus diisi"}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'kategori_id' => 'required|exists:kategori_bukus,id',
                'nama_buku' => 'required|string|max:255',
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'tahun_penerbitan' => 'sometimes|date',
                'jumlah_tersedia' => 'required|integer|min:1',
            ]);

            $buku = Buku::create($request->all());

            return response()->json([
                'message' => 'Buku berhasil ditambahkan',
                'data' => $buku
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data buku',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/buku/{id}",
     *     tags={"Buku"},
     *     summary="Menampilkan detail buku berdasarkan ID",
     *     operationId="bukuShow",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID buku",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data buku ditemukan",
     *         @OA\JsonContent(ref="#/components/schemas/BukuSchema")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Buku tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Buku tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat menampilkan detail buku"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $buku = Buku::with('kategori')->findOrFail($id);
            return response()->json($buku);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menampilkan detail buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/buku/{id}",
     *     tags={"Buku"},
     *     summary="Memperbarui data buku",
     *     operationId="bukuUpdate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID buku",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\RequestBody(
     *         description="Data buku yang akan diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="kategori_id", type="integer", example=1),
     *             @OA\Property(property="nama_buku", type="string", example="Harry Potter (Updated)"),
     *             @OA\Property(property="judul", type="string", example="Harry Potter and the Philosopher's Stone (Updated)"),
     *             @OA\Property(property="penulis", type="string", example="J.K. Rowling"),
     *             @OA\Property(property="penerbit", type="string", example="Gramedia"),
     *             @OA\Property(property="tahun_penerbitan", type="string", format="date", example="2001-01-01"),
     *             @OA\Property(property="jumlah_tersedia", type="integer", example=15)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Buku berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Buku berhasil diperbarui"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BukuSchema"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validasi gagal"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"nama_buku": {"Nama buku harus diisi"}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Buku tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Buku tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kategori_id' => 'sometimes|exists:kategori_bukus,id',
                'nama_buku' => 'sometimes|string|max:255',
                'judul' => 'sometimes|string|max:255',
                'penulis' => 'sometimes|string|max:255',
                'penerbit' => 'sometimes|string|max:255',
                'tahun_penerbitan' => 'sometimes|date',
                'jumlah_tersedia' => 'sometimes|integer|min:1',
            ]);

            $buku = Buku::findOrFail($id);
            $buku->update($request->all());

            return response()->json([
                'message' => 'Buku berhasil diperbarui',
                'data' => $buku
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data buku',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/buku/{id}",
     *     tags={"Buku"},
     *     summary="Menghapus buku berdasarkan ID",
     *     operationId="bukuDestroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID buku",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Buku berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Buku berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Buku tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Buku tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat menghapus buku"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $buku->delete();

            return response()->json([
                'message' => 'Buku berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menghapus buku',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
