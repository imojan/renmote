<x-guest-layout>
    @section('title', 'Verifikasi Email')

    <h1 class="auth-title">Verifikasi email</h1>
    <p class="auth-subtitle">Terima kasih telah mendaftar! Silakan verifikasi email kamu dengan klik link yang telah kami kirim. Belum menerima email?</p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-alert success">Link verifikasi baru telah dikirim ke email kamu.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="auth-submit-btn">
            <span class="btn-text">Kirim Ulang Email Verifikasi</span>
            <span class="spinner"></span>
        </button>
    </form>

    <div class="auth-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;font-size:0.9375rem;font-weight:700;color:#1a1a2e;text-decoration:underline;text-underline-offset:3px;cursor:pointer;font-family:inherit;">Keluar</button>
        </form>
    </div>
</x-guest-layout>
