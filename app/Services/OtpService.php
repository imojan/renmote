<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * OTP expiry in seconds (5 minutes).
     */
    private const OTP_TTL = 300;

    /**
     * OTP length (digits).
     */
    private const OTP_LENGTH = 6;

    /**
     * Generate a random numeric OTP.
     */
    public function generate(): string
    {
        return str_pad((string) random_int(0, pow(10, self::OTP_LENGTH) - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);
    }

    /**
     * Generate OTP, store hashed version in cache, and "send" via SMS (simulated).
     *
     * Cache key: otp:{phone}
     * TTL: 5 minutes
     */
    public function sendOtp(string $phone): string
    {
        $otp = $this->generate();

        // Store hashed OTP in cache for 5 minutes
        Cache::put(
            $this->cacheKey($phone),
            Hash::make($otp),
            self::OTP_TTL
        );

        // Store attempt counter for rate limiting (max resends)
        $attemptsKey = $this->attemptsKey($phone);
        $attempts = Cache::get($attemptsKey, 0);
        Cache::put($attemptsKey, $attempts + 1, self::OTP_TTL);

        // Simulate SMS sending — in production replace with actual SMS gateway
        // e.g., Twilio, Nexmo/Vonage, Zenziva, etc.
        Log::channel('single')->info("📱 [OTP SMS] Kode OTP untuk {$phone}: {$otp}");

        return $otp;
    }

    /**
     * Verify the OTP against the hashed value in cache.
     *
     * Returns true if valid, false otherwise.
     * On success, removes OTP from cache (single use).
     */
    public function verifyOtp(string $phone, string $otp): bool
    {
        $hashedOtp = Cache::get($this->cacheKey($phone));

        if (!$hashedOtp) {
            return false; // OTP expired or never sent
        }

        if (!Hash::check($otp, $hashedOtp)) {
            return false; // OTP mismatch
        }

        // OTP valid — clear from cache (single use)
        Cache::forget($this->cacheKey($phone));
        Cache::forget($this->attemptsKey($phone));

        return true;
    }

    /**
     * Check if phone has exceeded max OTP send attempts.
     */
    public function hasExceededAttempts(string $phone, int $max = 5): bool
    {
        return Cache::get($this->attemptsKey($phone), 0) >= $max;
    }

    /**
     * Cache key for OTP hash.
     */
    private function cacheKey(string $phone): string
    {
        return "otp:{$phone}";
    }

    /**
     * Cache key for send attempt counter.
     */
    private function attemptsKey(string $phone): string
    {
        return "otp_attempts:{$phone}";
    }
}
