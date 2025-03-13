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
     * Menghapus data petugas dari database.
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