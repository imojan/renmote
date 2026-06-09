<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Renmote</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; width: 100% !important;">

    <div style="background-color: #f8fafc; padding: 40px 16px; min-height: 100%;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 570px; margin: 0 auto;">
            
            <!-- Header Logo -->
            <tr>
                <td style="text-align: center; padding-bottom: 24px;">
                    <a href="{{ url('/') }}" style="display: inline-block; text-decoration: none;">
                        <img src="{{ isset($message) ? $message->embed(public_path('images/renmote-biru.png')) : asset('images/renmote-biru.png') }}" alt="Renmote Logo" style="height: 38px; max-height: 38px; width: auto; display: block; margin: 0 auto;">
                    </a>
                </td>
            </tr>

            <!-- Card Container -->
            <tr>
                <td style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05); padding: 32px 32px 32px 32px;">
                    <h1 style="color: #0f172a; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 16px; text-align: left; letter-spacing: -0.3px;">
                        Halo {{ $name }},
                    </h1>
                    
                    <p style="font-size: 15px; margin-bottom: 16px; color: #334155; line-height: 1.6; text-align: left;">
                        Kami menerima permintaan untuk mereset kata sandi (password) akun <strong>Renmote</strong> Anda.
                    </p>
                    
                    <p style="font-size: 15px; margin-bottom: 24px; color: #334155; line-height: 1.6; text-align: left;">
                        Silakan klik tombol di bawah ini untuk membuat kata sandi baru:
                    </p>
                    
                    <!-- Action Button -->
                    <div style="text-align: center; margin: 32px 0;">
                        <a href="{{ $resetUrl }}" style="background-color: #0f4ea9; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 4px 6px -1px rgba(15, 78, 169, 0.2);">
                            Reset Password
                        </a>
                    </div>

                    <!-- Callout Info Box -->
                    <div style="background-color: #eff6ff; border-left: 4px solid #0f4ea9; padding: 16px; margin: 28px 0; border-radius: 4px; text-align: left;">
                        <p style="margin: 0 0 6px 0; font-size: 13.5px; font-weight: 700; color: #1e3a8a;">
                            ⏰ Batas Waktu Tautan
                        </p>
                        <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #1e40af;">
                            Tautan reset password ini hanya berlaku selama <strong>15 menit</strong>. Jika Anda tidak meminta pengaturan ulang ini, silakan abaikan email ini dengan aman. Kata sandi Anda tidak akan berubah.
                        </p>
                    </div>

                    <!-- Fallback Link Section -->
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 24px; font-size: 12px; color: #64748b; line-height: 1.6; text-align: left;">
                        <p style="margin: 0 0 8px 0;">
                            Jika Anda mengalami kendala saat menekan tombol "Reset Password", silakan salin dan tempel tautan di bawah ini ke browser Anda:
                        </p>
                        <p style="margin: 0; word-break: break-all;">
                            <a href="{{ $resetUrl }}" style="color: #0f4ea9; text-decoration: underline; font-weight: 500;">{{ $resetUrl }}</a>
                        </p>
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="padding: 24px 32px; text-align: center; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                    <p style="margin: 0 0 4px 0;">&copy; {{ date('Y') }} Renmote. All rights reserved.</p>
                    <p style="margin: 0;">Aplikasi Penyewaan Kendaraan Terbaik.</p>
                </td>
            </tr>

        </table>
    </div>

</body>
</html>
