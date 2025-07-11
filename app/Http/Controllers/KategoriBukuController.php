<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;



/**
 * @OA\Info(
 *     title="API Kategori Buku",
 *     version="1.0",
 *     description="Dokumentasi API untuk pengelolaan kategori buku",
 *     @OA\Contact(
 *         email="contact@example.com",
 *         name="API Support"
 *     )
 * )
 */

/**
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
     *     security={{"bearerAuth":{}}},
     *     operationId="getKategoriBukuList",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data kategori",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="nama_kategori", type="string", example="Fiksi"),
     *                     @OA\Property(property="deskripsi", type="string", example="Buku-buku fiksi dan novel"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat mengambil data kategori"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
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
     *     security={{"bearerAuth":{}}},
     *     operationId="createKategoriBuku",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data kategori yang akan ditambahkan",
     *         @OA\JsonContent(
     *             required={"nama_kategori"},
     *             @OA\Property(property="nama_kategori", type="string", example="Novel"),
     *             @OA\Property(property="deskripsi", type="string", example="Kategori untuk buku novel")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kategori berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kategori berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="nama_kategori", type="string", example="Novel"),
     *                 @OA\Property(property="deskripsi", type="string", example="Kategori untuk buku novel"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
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
     *                 example={"nama_kategori": {"Nama kategori harus diisi"}}
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
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori = KategoriBuku::create($request->only(['nama_kategori', 'deskripsi']));

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ], 201);
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
     *     security={{"bearerAuth":{}}},
     *     operationId="getKategoriBukuById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID kategori",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menampilkan data kategori",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", example=1),
     *             @OA\Property(property="nama_kategori", type="string", example="Fiksi"),
     *             @OA\Property(property="deskripsi", type="string", example="Buku-buku fiksi dan novel"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kategori tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kategori tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat menampilkan detail kategori"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
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
     *     security={{"bearerAuth":{}}},
     *     operationId="updateKategoriBuku",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID kategori",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\RequestBody(
     *         description="Data kategori yang akan diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_kategori", type="string", example="Fiksi"),
     *             @OA\Property(property="deskripsi", type="string", example="Kategori untuk buku fiksi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kategori berhasil diperbarui"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="nama_kategori", type="string", example="Fiksi"),
     *                 @OA\Property(property="deskripsi", type="string", example="Kategori untuk buku fiksi"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kategori tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kategori tidak ditemukan")
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
     *                 example={"nama_kategori": {"Nama kategori harus diisi"}}
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
    public function update(Request $request, $id)
{
    try {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori = KategoriBuku::findOrFail($id);
        $kategori->update($request->only(['nama_kategori', 'deskripsi']));

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $kategori
        ]);
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
     *     security={{"bearerAuth":{}}},
     *     operationId="deleteKategoriBuku",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID kategori",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kategori berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kategori tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kategori tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat menghapus kategori"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
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
