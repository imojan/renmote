<x-guest-layout>
    @section('title', 'Daftar')

    <h1 class="auth-title">Buat akun untuk<br>mulai sewa</h1>
    <p class="auth-subtitle">Daftar gratis di Renmote</p>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="auth-alert error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Google OAuth --}}
    <a href="{{ url('/auth/google') }}" class="auth-social-btn">
        <svg width="22" height="22" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Daftar dengan Google
    </a>

    {{-- Divider --}}
    <div class="auth-divider">
        <span>atau</span>
    </div>

    {{-- Register Form --}}
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        {{-- Role Picker --}}
        <div class="auth-field">
            <label>Daftar sebagai</label>
            <div class="auth-role-picker">
                <div class="auth-role-card {{ old('role', 'user') === 'user' ? 'active' : '' }}" onclick="selectRole(this, 'user')">
                    <input type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                    <svg class="role-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="role-title">Penyewa</span>
                    <span class="role-desc">Sewa motor mudah</span>
                </div>
                <div class="auth-role-card {{ old('role') === 'vendor' ? 'active' : '' }}" onclick="selectRole(this, 'vendor')">
                    <input type="radio" name="role" value="vendor" {{ old('role') === 'vendor' ? 'checked' : '' }}>
                    <svg class="role-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="role-title">Vendor</span>
                    <span class="role-desc">Sewakan motor Anda</span>
                </div>
            </div>
            @if ($errors->has('role'))
                <div class="field-error">{{ $errors->first('role') }}</div>
            @endif
        </div>

        {{-- Name --}}
        <div class="auth-field">
            <label for="name">Nama lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   placeholder="Masukkan nama lengkap" required autofocus autocomplete="name">
            @if ($errors->has('name'))
                <div class="field-error">{{ $errors->first('name') }}</div>
            @endif
        </div>

        {{-- Email --}}
        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@email.com" required autocomplete="username">
            @if ($errors->has('email'))
                <div class="field-error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        {{-- Phone Number --}}
        <div class="auth-field">
            <label for="phone_number">Nomor HP</label>
            <input id="phone_number" type="tel" name="phone_number" value="{{ old('phone_number') }}"
                   placeholder="08xxxxxxxxxx" autocomplete="tel">
            @if ($errors->has('phone_number'))
                <div class="field-error">{{ $errors->first('phone_number') }}</div>
            @endif
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <label for="reg_password">Password</label>
            <div class="password-wrapper">
                <input id="reg_password" type="password" name="password"
                       placeholder="Minimal 8 karakter" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('reg_password', this)" aria-label="Tampilkan password">
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
            @if ($errors->has('password'))
                <div class="field-error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        {{-- Confirm Password --}}
        <div class="auth-field">
            <label for="password_confirmation">Konfirmasi password</label>
            <div class="password-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       placeholder="Ulangi password" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan password">
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

        <button type="submit" class="auth-submit-btn" id="registerBtn">
            <span class="btn-text">Daftar</span>
            <span class="spinner"></span>
        </button>
    </form>

    {{-- Footer --}}
    <div class="auth-footer">
        <p>Sudah punya akun?</p>
        <a href="{{ route('login') }}">Masuk</a>
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

        function selectRole(card, value) {
            document.querySelectorAll('.auth-role-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            card.querySelector('input[type="radio"]').checked = true;
        }

        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('registerBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
    @endpush
</x-guest-layout>
