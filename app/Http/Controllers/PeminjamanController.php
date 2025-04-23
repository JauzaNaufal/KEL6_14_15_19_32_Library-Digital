<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Peminjaman",
 *     description="Manajemen peminjaman buku"
 * )
 */
class PeminjamanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/peminjaman",
     *     tags={"Peminjaman"},
     *     summary="Menampilkan semua data peminjaman",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menampilkan daftar peminjaman"
     *     )
     * )
     */
    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->get();
        return response()->json($peminjaman);
    }

    /**
     * @OA\Post(
     *     path="/api/peminjaman",
     *     tags={"Peminjaman"},
     *     summary="Membuat data peminjaman baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"anggota_id", "buku_id", "petugas_id", "tanggal_peminjaman", "status"},
     *             @OA\Property(property="anggota_id", type="integer", example=1),
     *             @OA\Property(property="buku_id", type="integer", example=3),
     *             @OA\Property(property="petugas_id", type="integer", example=2),
     *             @OA\Property(property="tanggal_peminjaman", type="string", format="date", example="2025-04-23"),
     *             @OA\Property(property="tanggal_pengembalian", type="string", format="date", example="2025-05-01"),
     *             @OA\Property(property="status", type="string", enum={"dipinjam", "dikembalikan"}, example="dipinjam")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Peminjaman berhasil ditambahkan"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Buku tidak tersedia"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'buku_id' => 'required|exists:bukus,id',
            'petugas_id' => 'required|exists:petugas,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan'
        ]);

        $buku = Buku::find($request->buku_id);

        if ($buku->jumlah_tersedia < 1) {
            return response()->json(['message' => 'Buku tidak tersedia untuk dipinjam'], 400);
        }

        $peminjaman = Peminjaman::create($request->all());

        $buku->decrement('jumlah_tersedia');

        return response()->json([
            'message' => 'Peminjaman berhasil ditambahkan!',
            'peminjaman' => $peminjaman
        ], 201);
    }
}
