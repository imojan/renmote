<x-guest-layout>
    @section('title', 'Masuk')

    <h1 class="auth-title">Selamat datang<br>kembali</h1>
    <p class="auth-subtitle">Masuk ke akun Renmote kamu</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="auth-alert success">{{ session('status') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="auth-alert error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@email.com" required autofocus autocomplete="username">
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password"
                       placeholder="••••••••" required autocomplete="current-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)" aria-label="Tampilkan password">
                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="auth-options">
            <label class="auth-remember">
                <input type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-forgot">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="auth-submit-btn" id="loginBtn">
            <span class="btn-text">Masuk</span>
            <span class="spinner"></span>
        </button>
    </form>

    {{-- Divider --}}
    <div class="auth-divider">
        <span>atau</span>
    </div>

    {{-- Google OAuth --}}
    <a href="{{ url('/auth/google') }}" class="auth-social-btn">
        <svg width="22" height="22" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Masuk dengan Google
    </a>

    {{-- Footer --}}
    <div class="auth-footer">
        <p>Belum punya akun?</p>
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </div>

    @push('scripts')
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
    @endpush
</x-guest-layout>
