<x-guest-layout>
    @section('title', 'Masuk')

    <x-auth.title subtitle="Masuk ke akun Renmote kamu">
        Selamat datang<br>kembali
    </x-auth.title>

    @if (session('status'))
        <x-auth.alert type="success">{{ session('status') }}</x-auth.alert>
    @endif

    @if ($errors->any())
        <x-auth.alert type="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-auth.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" data-rn-loading>
        @csrf

        <x-auth.field label="Email" for="email">
            <x-auth.input id="email" type="email" name="email"
                          value="{{ old('email') }}"
                          placeholder="nama@email.com"
                          required autofocus autocomplete="username" />
        </x-auth.field>

        <x-auth.field label="Password" for="password">
            <x-auth.password-input id="password"
                                   placeholder="••••••••"
                                   required autocomplete="current-password" />
        </x-auth.field>

        <div class="mb-1 flex items-center justify-between text-[0.8125rem]">
            <label class="flex cursor-pointer items-center gap-2">
                <input type="checkbox" name="remember"
                       class="h-4 w-4 cursor-pointer rounded border-[1.5px] border-gray-300 text-rn-primary accent-rn-primary focus:ring-0">
                <span class="font-medium text-gray-700">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="font-medium text-gray-500 underline underline-offset-2 transition-colors hover:text-rn-text">
                    Lupa password?
                </a>
            @endif
        </div>

        <x-auth.submit-button>Masuk</x-auth.submit-button>
    </form>

    <x-auth.divider>atau</x-auth.divider>

    <x-auth.social-button :href="url('/auth/google')">
        <svg width="22" height="22" viewBox="0 0 24 24" class="h-[22px] w-[22px] flex-shrink-0">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Masuk dengan Google
    </x-auth.social-button>

    <x-auth.footer text="Belum punya akun?">
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </x-auth.footer>
</x-guest-layout>
