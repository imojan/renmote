<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;

class OtpController extends Controller
{
    public function __construct(
        private OtpService $otpService
    ) {}

    /**
     * Send OTP to the user's phone number.
     *
     * Rate limiting: Apply 'throttle:5,5' middleware on this route
     * to limit to 5 requests per 5 minutes per user/IP.
     * Additionally, the OtpService tracks per-phone attempts (max 5 within TTL).
     */
    public function send(SendOtpRequest $request): JsonResponse
    {
        $phone = $request->normalizedPhone();
        $user = $request->user();

        // Check per-phone rate limit (max 5 OTP sends within 5 min window)
        if ($this->otpService->hasExceededAttempts($phone, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak permintaan OTP. Coba lagi dalam beberapa menit.',
            ], 429);
        }

        // Update user's phone number (not yet verified)
        $user->update([
            'phone_number'      => $phone,
            'is_phone_verified' => false,
        ]);

        // Generate & send OTP (simulated via Log)
        $this->otpService->sendOtp($phone);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke ' . $this->maskPhone($phone) . '.',
            // In development, you can check storage/logs/laravel.log for the OTP
        ]);
    }

    /**
     * Verify OTP and mark user's phone as verified.
     *
     * Rate limiting: Apply 'throttle:10,5' middleware on this route
     * to limit verification attempts.
     */
    public function verify(VerifyOtpRequest $request): JsonResponse
    {
        $phone = $request->normalizedPhone();
        $user = $request->user();

        // Verify phone matches user's stored phone
        if ($user->phone_number !== $phone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor telepon tidak sesuai dengan yang terdaftar.',
            ], 422);
        }

        // Verify OTP
        $isValid = $this->otpService->verifyOtp($phone, $request->otp);

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa.',
            ], 422);
        }

        // Mark phone as verified
        $user->update([
            'is_phone_verified' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nomor telepon berhasil diverifikasi!',
        ]);
    }

    /**
     * Mask phone number for display: 0812****5678
     */
    private function maskPhone(string $phone): string
    {
        $len = strlen($phone);
        if ($len <= 6) return $phone;

        return substr($phone, 0, 4) . str_repeat('*', $len - 8) . substr($phone, -4);
    }
}
