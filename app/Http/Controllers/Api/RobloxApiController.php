<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RobloxMission;
use App\Models\RobloxClaim;
use App\Models\Banner;
use Illuminate\Support\Str;

class RobloxApiController extends Controller
{
    /**
     * Mengambil seluruh data misi Roblox & Banner aktif
     */
    public function index(): JsonResponse
    {
        try {
            $missions = RobloxMission::where('is_active', true)
                ->latest()
                ->get();

            $banner = Banner::where('page_key', 'roblox')
                ->where('is_active', true)
                ->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengambil data Roblox',
                'data' => [
                    'banner' => $banner,
                    'missions' => $missions,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menerima submit bukti screenshot klaim badge & reward dari user
     */
    public function submitClaim(Request $request): JsonResponse
    {
        $request->validate([
            'roblox_mission_id' => 'required|exists:roblox_missions,id',
            'roblox_username' => 'required|string|max:100',
            'whatsapp_number' => 'required|string|max:20',
            'proof_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'roblox_mission_id.required' => 'Misi wajib dipilih.',
            'roblox_username.required' => 'Username Roblox wajib diisi.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'proof_image.required' => 'Screenshot bukti pencapaian wajib diunggah.',
            'proof_image.max' => 'Ukuran file screenshot maksimal 5 MB.',
        ]);

        // Cek apakah akun Roblox ini sudah pernah mengajukan klaim untuk badge yang sama
        $existing = RobloxClaim::where('roblox_mission_id', $request->roblox_mission_id)
            ->where('roblox_username', $request->roblox_username)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Roblox ini sudah pernah mengajukan klaim untuk badge ini.'
            ], 422);
        }

        // Simpan screenshot bukti ke storage public
        $imagePath = $request->file('proof_image')->store('roblox-proofs', 'public');

        // Buat kode unik klaim untuk ditunjukkan ke kasir cafe
        $claimCode = 'TMC-' . strtoupper(Str::random(6));

        $claim = RobloxClaim::create([
            'roblox_mission_id' => $request->roblox_mission_id,
            'roblox_username' => $request->roblox_username,
            'whatsapp_number' => $request->whatsapp_number,
            'proof_image' => $imagePath,
            'claim_code' => $claimCode,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bukti pencapaian berhasil dikirim! Silakan tunggu verifikasi admin.',
            'data' => $claim
        ], 201);
    }
}