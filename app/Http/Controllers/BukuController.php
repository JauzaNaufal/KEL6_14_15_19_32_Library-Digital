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
 * @OA\Schema(
 *     schema="KategoriBukuSchema",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="nama_kategori", type="string", example="Fiksi"),
 *         @OA\Property(property="deskripsi", type="string", example="Buku-buku fiksi dan novel"),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="BukuSchema",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="kategori_id", type="integer", example=1),
 *         @OA\Property(property="nama_buku", type="string", example="Harry Potter"),
 *         @OA\Property(property="judul", type="string", example="Harry Potter and the Philosopher's Stone"),
 *         @OA\Property(property="penulis", type="string", example="J.K. Rowling"),
 *         @OA\Property(property="penerbit", type="string", example="Gramedia"),
 *         @OA\Property(property="tahun_penerbitan", type="string", format="date", example="2001-01-01"),
 *         @OA\Property(property="jumlah_tersedia", type="integer", example=10),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time"),
 *         @OA\Property(
 *             property="kategori",
 *             ref="#/components/schemas/KategoriBukuSchema"
 *         )
 *     }
 * )
 *
 * Controller for managing books (Buku) in the system
 */
class BukuController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/buku",
     *     tags={"Buku"},
     *     summary="Menampilkan semua buku",
     *     security={{"bearerAuth":{}}},
     *     operationId="bukuIndex",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data buku",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Berhasil mengambil data buku"),
     *             @OA\Property(
     *                 property="buku",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BukuSchema")
     *             )
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
            return response()->json([
                'message' => 'Berhasil mengambil data buku',
                'buku' => $buku
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/buku/kategori-list",
     *     tags={"Buku"},
     *     summary="Menampilkan semua kategori buku",
     *     security={{"bearerAuth":{}}},
     *     operationId="bukuKategoriList",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data kategori",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Berhasil mengambil data kategori"),
     *             @OA\Property(
     *                 property="kategori",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/KategoriBukuSchema")
     *             )
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
            return response()->json([
                'message' => 'Berhasil mengambil data kategori',
                'kategori' => $kategori
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/buku/search",
     *     tags={"Buku"},
     *     summary="Mencari buku berdasarkan judul",
     *     security={{"bearerAuth":{}}},
     *     operationId="bukuSearch",
     *     @OA\Parameter(
     *         name="judul",
     *         in="query",
     *         description="Kata kunci judul buku",
     *         required=true,
     *         @OA\Schema(type="string", example="Harry Potter")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menemukan buku",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Berhasil mencari buku berdasarkan judul"),
     *             @OA\Property(
     *                 property="buku",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BukuSchema")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Buku tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tidak ada buku yang ditemukan dengan judul tersebut")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat mencari buku"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function search(Request $request)
    {
        try {
            // Validasi parameter judul
            $request->validate([
                'judul' => 'required|string'
            ]);

            $keyword = $request->input('judul');

            // Cari buku berdasarkan judul menggunakan LIKE untuk pencarian parsial
            $buku = Buku::with('kategori')
                ->where('judul', 'LIKE', '%' . $keyword . '%')
                ->orWhere('nama_buku', 'LIKE', '%' . $keyword . '%')
                ->get();

            // Jika tidak ada buku yang ditemukan
            if ($buku->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada buku yang ditemukan dengan judul tersebut'
                ], 404);
            }

            return response()->json([
                'message' => 'Berhasil mencari buku berdasarkan judul',
                'buku' => $buku
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mencari buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/buku/kategori/{id}",
     *     tags={"Buku"},
     *     summary="Menampilkan buku berdasarkan ID kategori",
     *     security={{"bearerAuth":{}}},
     *     operationId="bukuByKategori",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID kategori buku",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data buku berdasarkan kategori",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Berhasil mengambil data buku berdasarkan kategori"),
     *             @OA\Property(
     *                 property="kategori",
     *                 ref="#/components/schemas/KategoriBukuSchema"
     *             ),
     *             @OA\Property(
     *                 property="buku",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BukuSchema")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kategori tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kategori buku tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat mengambil data buku"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function getBooksByCategory($id)
    {
        try {
            // Cari kategori terlebih dahulu untuk memastikan kategori tersebut ada
            $kategori = KategoriBuku::findOrFail($id);

            // Ambil semua buku yang memiliki kategori_id yang sesuai
            $buku = Buku::where('kategori_id', $id)->get();

            return response()->json([
                'message' => 'Berhasil mengambil data buku berdasarkan kategori',
                'kategori' => $kategori,
                'buku' => $buku
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Kategori buku tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/buku",
     *     tags={"Buku"},
     *     summary="Menambahkan buku baru",
     *     security={{"bearerAuth":{}}},
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
     *     security={{"bearerAuth":{}}},
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
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Berhasil mengambil detail buku"),
     *             @OA\Property(property="buku", ref="#/components/schemas/BukuSchema")
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
            return response()->json([
                'message' => 'Berhasil mengambil detail buku',
                'buku' => $buku
            ]);
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
     *     security={{"bearerAuth":{}}},
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
     *     security={{"bearerAuth":{}}},
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
