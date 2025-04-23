<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Info(
 *     title="API Kategori Buku",
 *     version="1.0",
 *     description="Dokumentasi API untuk pengelolaan kategori buku"
 * )
 *
 * @OA\Tag(
 *     name="KategoriBuku",
 *     description="Manajemen Kategori Buku"
 * )
 */
class KategoriBukuController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/kategori",
     *     tags={"KategoriBuku"},
     *     summary="Menampilkan semua kategori",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data kategori"
     *     )
     * )
     */
    public function index()
    {
        try {
            $kategori = KategoriBuku::all();
            return response()->json([
                "data" => $kategori
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/kategori",
     *     tags={"KategoriBuku"},
     *     summary="Menambahkan kategori baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_kategori"},
     *             @OA\Property(property="nama_kategori", type="string", example="Novel"),
     *             @OA\Property(property="deskripsi", type="string", example="Kategori untuk buku novel")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kategori berhasil ditambahkan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $kategori = KategoriBuku::create($request->all());

            return response()->json([
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $kategori
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data kategori',
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
     *     path="/api/kategori/{id}",
     *     tags={"KategoriBuku"},
     *     summary="Menampilkan detail kategori berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menampilkan data kategori"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kategori tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $kategori = KategoriBuku::findOrFail($id);
            return response()->json($kategori);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menampilkan detail kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/kategori/{id}",
     *     tags={"KategoriBuku"},
     *     summary="Memperbarui kategori berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_kategori", type="string", example="Fiksi"),
     *             @OA\Property(property="deskripsi", type="string", example="Kategori untuk buku fiksi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori berhasil diperbarui"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kategori tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_kategori' => 'sometimes|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $kategori = KategoriBuku::findOrFail($id);
            $kategori->update($request->all());

            return response()->json([
                'message' => 'Kategori berhasil diperbarui',
                'data' => $kategori
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data kategori',
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
     *     path="/api/kategori/{id}",
     *     tags={"KategoriBuku"},
     *     summary="Menghapus kategori berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kategori tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $kategori = KategoriBuku::findOrFail($id);
            $kategori->delete();

            return response()->json([
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
