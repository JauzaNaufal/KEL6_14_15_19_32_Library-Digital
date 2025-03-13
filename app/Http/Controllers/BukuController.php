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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kategori_id' => 'required|exists:kategori_buku,id',
                'nama_buku' => 'required|string|max:255',
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'tahun_penerbitan' => 'required|date',
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

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kategori_id' => 'sometimes|exists:kategori_buku,id',
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