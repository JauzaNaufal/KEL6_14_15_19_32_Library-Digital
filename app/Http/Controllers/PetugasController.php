<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PetugasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/petugas",
     *     summary="Menampilkan semua data petugas",
     *     tags={"Petugas"},
     *     @OA\Response(
     *         response=200,
     *         description="Data petugas berhasil diambil"
     *     )
     * )
     */
    public function index()
    {
        try {
            $petugas = Petugas::all();
            return response()->json($petugas);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data petugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/petugas",
     *     summary="Menambahkan data petugas baru",
     *     tags={"Petugas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_petugas","posisi","nomor_telepon","email"},
     *             @OA\Property(property="nama_petugas", type="string"),
     *             @OA\Property(property="posisi", type="string"),
     *             @OA\Property(property="nomor_telepon", type="integer"),
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Petugas berhasil ditambahkan"
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
                'nama_petugas' => 'required|string|max:255',
                'posisi' => 'required|string|max:255',
                'nomor_telepon' => 'required|numeric',
                'email' => 'required|email|unique:petugas',
            ]);

            $petugas = Petugas::create($request->all());

            return response()->json([
                'message' => 'Petugas berhasil ditambahkan',
                'data' => $petugas
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data petugas',
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
     *     path="/api/petugas/{id}",
     *     summary="Menampilkan detail petugas berdasarkan ID",
     *     tags={"Petugas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail petugas ditemukan"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Petugas tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $petugas = Petugas::findOrFail($id);
            return response()->json($petugas);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Petugas tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menampilkan detail petugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/petugas/{id}",
     *     summary="Memperbarui data petugas",
     *     tags={"Petugas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_petugas", type="string"),
     *             @OA\Property(property="posisi", type="string"),
     *             @OA\Property(property="nomor_telepon", type="integer"),
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Petugas berhasil diperbarui"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Petugas tidak ditemukan"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_petugas' => 'sometimes|string|max:255',
                'posisi' => 'sometimes|string|max:255',
                'nomor_telepon' => 'sometimes|numeric',
                'email' => 'sometimes|email|unique:petugas,email,' . $id,
            ]);

            $petugas = Petugas::findOrFail($id);
            $petugas->update($request->all());

            return response()->json([
                'message' => 'Petugas berhasil diperbarui',
                'data' => $petugas
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Petugas tidak ditemukan'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data petugas',
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
     *     path="/api/petugas/{id}",
     *     summary="Menghapus data petugas",
     *     tags={"Petugas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Petugas berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Petugas tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $petugas = Petugas::findOrFail($id);
            $petugas->delete();

            return response()->json([
                'message' => 'Petugas berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Petugas tidak ditemukan'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menghapus petugas',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus petugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}