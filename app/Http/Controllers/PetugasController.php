<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Registrasi petugas baru",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_petugas","posisi","nomor_telepon","email","password","password_confirmation"},
     *             @OA\Property(property="nama_petugas", type="string", example="John Doe"),
     *             @OA\Property(property="posisi", type="string", example="Admin"),
     *             @OA\Property(property="nomor_telepon", type="string", example="081234567890"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registrasi berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registrasi berhasil"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'nama_petugas' => 'required|string|max:255',
                'posisi' => 'required|string|max:255',
                'nomor_telepon' => 'required|string|max:20',
                'email' => 'required|email|unique:petugas,email',
                'password' => 'required|string|min:8',
            ]);

            $petugas = Petugas::create([
                'nama_petugas' => $request->nama_petugas,
                'posisi' => $request->posisi,
                'nomor_telepon' => $request->nomor_telepon,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $petugas->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Registrasi berhasil',
                'user' => $petugas,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal melakukan registrasi',
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
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login petugas",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login berhasil"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Kredensial tidak valid"
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $petugas = Petugas::where('email', $request->email)->first();

            if (!$petugas || !Hash::check($request->password, $petugas->password)) {
                return response()->json([
                    'message' => 'Email atau password tidak valid'
                ], 401);
            }

            // Hapus token lama
            $petugas->tokens()->delete();

            $token = $petugas->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'user' => $petugas,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout petugas",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout berhasil")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout berhasil'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/auth/profile",
     *     summary="Mendapatkan profil petugas yang sedang login",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data profil berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function profile(Request $request)
    {
        try {
            return response()->json([
                'user' => $request->user()
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/auth/update-profile",
     *     summary="Update profil petugas yang sedang login",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_petugas", type="string", example="John Doe Updated"),
     *             @OA\Property(property="posisi", type="string", example="Super Admin"),
     *             @OA\Property(property="nomor_telepon", type="string", example="081234567891"),
     *             @OA\Property(property="email", type="string", format="email", example="john.updated@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profil berhasil diperbarui"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'nama_petugas' => 'sometimes|string|max:255',
                'posisi' => 'sometimes|string|max:255',
                'nomor_telepon' => 'sometimes|string|max:20',
                'email' => 'sometimes|email|unique:petugas,email,' . $user->id,
            ]);

            $user->update($request->only(['nama_petugas', 'posisi', 'nomor_telepon', 'email']));

            return response()->json([
                'message' => 'Profil berhasil diperbarui',
                'user' => $user->fresh()
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui profil',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/auth/change-password",
     *     summary="Mengubah password petugas yang sedang login",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","password","password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password berhasil diubah",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password berhasil diubah")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi atau password lama salah"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Password lama tidak sesuai'
                ], 401);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Hapus semua token untuk memaksa login ulang
            $user->tokens()->delete();

            return response()->json([
                'message' => 'Password berhasil diubah. Silakan login kembali.'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengubah password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
