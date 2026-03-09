<x-guest-layout>
    @section('title', 'Reset Password')

    <h1 class="auth-title">Reset password</h1>
    <p class="auth-subtitle">Buat password baru untuk akun kamu.</p>

    @if ($errors->any())
        <div class="auth-alert error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" id="resetForm">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   required autofocus autocomplete="username">
        </div>

        <div class="auth-field">
            <label for="password">Password baru</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password"
                       placeholder="Minimal 8 karakter" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        <div class="auth-field">
            <label for="password_confirmation">Konfirmasi password</label>
            <div class="password-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       placeholder="Ulangi password" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="auth-submit-btn" id="resetBtn">
            <span class="btn-text">Reset Password</span>
            <span class="spinner"></span>
        </button>
    </form>

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

        document.getElementById('resetForm').addEventListener('submit', function() {
            const btn = document.getElementById('resetBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
    @endpush
</x-guest-layout>
