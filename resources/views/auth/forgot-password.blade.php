<x-guest-layout>
    @section('title', 'Lupa Password')

    <h1 class="auth-title">Lupa password?</h1>
    <p class="auth-subtitle">Masukkan email kamu dan kami akan kirimkan link untuk reset password.</p>

    @if (session('status'))
        <div class="auth-alert success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="auth-alert error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@email.com" required autofocus>
        </div>

        <button type="submit" class="auth-submit-btn" id="forgotBtn">
            <span class="btn-text">Kirim Link Reset</span>
            <span class="spinner"></span>
        </button>
    </form>

    <div class="auth-footer">
        <p>Ingat password kamu?</p>
        <a href="{{ route('login') }}">Kembali ke login</a>
    </div>

    @push('scripts')
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = document.getElementById('forgotBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
    @endpush
</x-guest-layout>
