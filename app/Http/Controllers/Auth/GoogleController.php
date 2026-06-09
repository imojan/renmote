<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            // Menggunakan stateless() untuk menghindari InvalidStateException
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // 1. Cari user berdasarkan google_id
            $user = User::where('google_id', $googleUser->getId())
                ->whereNull('deleted_at')
                ->first();

            if ($user) {
                // Update token jika diperlukan
                $user->update([
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken ?? $user->google_refresh_token,
                ]);
            } else {
                // 2. Cari user berdasarkan email
                $user = User::where('email', $googleUser->getEmail())
                    ->whereNull('deleted_at')
                    ->first();

                if ($user) {
                    // Hubungkan akun yang sudah ada dengan Google ID
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken ?? $user->google_refresh_token,
                    ]);
                } else {
                    // 3. Buat user baru jika tidak ada
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'password' => Hash::make(Str::random(24)), // Random password aman
                        'role' => 'user', // Default role untuk pendaftaran otomatis
                        'google_id' => $googleUser->getId(),
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                    ]);

                    event(new Registered($user));
                }
            }

            // Login user
            Auth::login($user, true);

            // Regenerasi session agar aman
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if ($user->role === 'vendor' && !$user->vendor) {
                return redirect()->intended(route('vendor.register', absolute: false));
            }

            $fallbackUrl = match ($user->role) {
                'admin'  => route('admin.dashboard'),
                'vendor' => route('vendor.dashboard'),
                default  => route('user.dashboard'),
            };

            return redirect()->intended($fallbackUrl);

        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Gagal masuk menggunakan Google. Silakan coba lagi atau gunakan login manual.',
            ]);
        }
    }
}
