<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class AnggotaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/anggotas",
     *     summary="Menampilkan semua data anggota",
     *     security={{"bearerAuth":{}}},
     *     tags={"Anggota"},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar anggota berhasil diambil"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan saat mengambil data"
     *     )
     * )
     */

    public function index()
    {
        try {
            $anggotas = Anggota::all();
            return response()->json($anggotas);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data anggota!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/anggotas",
     *     summary="Menyimpan data anggota baru",
     *     security={{"bearerAuth":{}}},
     *     tags={"Anggota"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama", "nomor_telepon", "email", "tanggal_bergabung"},
     *             @OA\Property(property="nama", type="string", example="Budi"),
     *             @OA\Property(property="nomor_telepon", type="string", example="08123456789"),
     *             @OA\Property(property="email", type="string", format="email", example="budi@example.com"),
     *             @OA\Property(property="tanggal_bergabung", type="string", format="date", example="2024-01-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Anggota berhasil ditambahkan"
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
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'nomor_telepon' => 'required|string|max:15',
                'email' => 'required|email|unique:anggotas,email',
                'tanggal_bergabung' => 'required|date',
            ]);

            $anggota = Anggota::create($request->all());

            return response()->json([
                'message' => 'Anggota berhasil ditambahkan!',
                'anggota' => $anggota
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data anggota!',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/anggotas/{id}",
     *     summary="Menampilkan detail anggota",
     *     security={{"bearerAuth":{}}},
     *     tags={"Anggota"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID anggota",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail anggota"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Anggota tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan"
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $anggota = Anggota::find($id);

            if (!$anggota) {
                return response()->json(['message' => 'Anggota tidak ditemukan!'], 404);
            }

            return response()->json($anggota);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data anggota!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/anggotas/{id}",
     *     summary="Memperbarui data anggota",
     *     security={{"bearerAuth":{}}},
     *     tags={"Anggota"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID anggota",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="Andi"),
     *             @OA\Property(property="nomor_telepon", type="string", example="08123456789"),
     *             @OA\Property(property="email", type="string", format="email", example="andi@example.com"),
     *             @OA\Property(property="tanggal_bergabung", type="string", format="date", example="2024-01-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Anggota berhasil diperbarui"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Anggota tidak ditemukan"
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
        try {
            $anggota = Anggota::find($id);

            if (!$anggota) {
                return response()->json(['message' => 'Anggota tidak ditemukan!'], 404);
            }

            $request->validate([
                'nama' => 'sometimes|string|max:255',
                'nomor_telepon' => 'sometimes|string|max:15',
                'email' => 'sometimes|email|unique:anggotas,email,' . $id,
                'tanggal_bergabung' => 'sometimes|date',
            ]);

            $anggota->update($request->all());

            return response()->json([
                'message' => 'Anggota berhasil diperbarui!',
                'anggota' => $anggota
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data anggota!',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/anggotas/{id}",
     *     summary="Menghapus data anggota",
     *     security={{"bearerAuth":{}}},
     *     tags={"Anggota"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID anggota",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Anggota berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Anggota tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan"
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $anggota = Anggota::find($id);

            if (!$anggota) {
                return response()->json(['message' => 'Anggota tidak ditemukan!'], 404);
            }

            $anggota->delete();

            return response()->json(['message' => 'Anggota berhasil dihapus!']);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menghapus data anggota!',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
