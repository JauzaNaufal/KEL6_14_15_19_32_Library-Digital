<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KategoriBukuController extends Controller
{
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
