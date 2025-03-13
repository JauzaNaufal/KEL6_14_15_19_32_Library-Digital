<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class AnggotaController extends Controller
{
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