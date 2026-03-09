<x-guest-layout>
    @section('title', 'Konfirmasi Password')

    <h1 class="auth-title">Konfirmasi password</h1>
    <p class="auth-subtitle">Ini adalah area aman. Silakan konfirmasi password kamu sebelum melanjutkan.</p>

    @if ($errors->any())
        <div class="auth-alert error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" id="confirmForm">
        @csrf

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password"
                       placeholder="Masukkan password" required autocomplete="current-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="auth-submit-btn" id="confirmBtn">
            <span class="btn-text">Konfirmasi</span>
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

        document.getElementById('confirmForm').addEventListener('submit', function() {
            const btn = document.getElementById('confirmBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
    @endpush
</x-guest-layout>
