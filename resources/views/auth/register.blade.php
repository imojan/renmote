<x-guest-layout>
    @section('title', 'Daftar')

    <x-auth.title subtitle="Daftar gratis di Renmote">
        Buat akun untuk<br>mulai sewa
    </x-auth.title>

    @if ($errors->any())
        <x-auth.alert type="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-auth.alert>
    @endif

    <x-auth.social-button :href="url('/auth/google')">
        <svg width="22" height="22" viewBox="0 0 24 24" class="h-[22px] w-[22px] flex-shrink-0">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Daftar dengan Google
    </x-auth.social-button>

    <x-auth.divider>atau</x-auth.divider>

    <form method="POST" action="{{ route('register') }}" data-rn-loading>
        @csrf

        {{-- Role picker --}}
        <x-auth.field label="Daftar sebagai" :error="$errors->first('role')">
            <div class="grid grid-cols-2 gap-3">
                <x-auth.role-card name="role" value="user" title="Penyewa" description="Sewa motor mudah"
                                  :checked="old('role', 'user') === 'user'">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </x-auth.role-card>

                <x-auth.role-card name="role" value="vendor" title="Vendor" description="Sewakan motor Anda"
                                  :checked="old('role') === 'vendor'">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </x-auth.role-card>
            </div>
        </x-auth.field>

        <x-auth.field label="Nama lengkap" for="name" :error="$errors->first('name')">
            <x-auth.input id="name" type="text" name="name"
                          value="{{ old('name') }}"
                          placeholder="Masukkan nama lengkap"
                          required autofocus autocomplete="name" />
        </x-auth.field>

        <x-auth.field label="Email" for="email" :error="$errors->first('email')">
            <x-auth.input id="email" type="email" name="email"
                          value="{{ old('email') }}"
                          placeholder="nama@email.com"
                          required autocomplete="username" />
        </x-auth.field>

        <x-auth.field label="Nomor HP" for="phone_number" :error="$errors->first('phone_number')">
            <x-auth.input id="phone_number" type="tel" name="phone_number"
                          value="{{ old('phone_number') }}"
                          placeholder="08xxxxxxxxxx"
                          autocomplete="tel" />
        </x-auth.field>

        <x-auth.field label="Password" for="reg_password" :error="$errors->first('password')">
            <x-auth.password-input id="reg_password"
                                   placeholder="Minimal 8 karakter"
                                   required autocomplete="new-password" />
        </x-auth.field>

        <x-auth.field label="Konfirmasi password" for="password_confirmation">
            <x-auth.password-input id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi password"
                                   required autocomplete="new-password" />
        </x-auth.field>

        <x-auth.submit-button>Daftar</x-auth.submit-button>
    </form>

    <x-auth.footer text="Sudah punya akun?">
        <a href="{{ route('login') }}">Masuk</a>
    </x-auth.footer>
</x-guest-layout>
