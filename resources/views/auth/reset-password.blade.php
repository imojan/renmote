<x-guest-layout>
    @section('title', 'Reset Password')

    <x-auth.title subtitle="Buat password baru untuk akun kamu.">
        Reset password
    </x-auth.title>

    @if ($errors->any())
        <x-auth.alert type="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-auth.alert>
    @endif

    <form method="POST" action="{{ route('password.store') }}" data-rn-loading>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-auth.field label="Email" for="email">
            <x-auth.input id="email" type="email" name="email"
                          value="{{ old('email', $request->email) }}"
                          required autofocus autocomplete="username" />
        </x-auth.field>

        <x-auth.field label="Password baru" for="password">
            <x-auth.password-input id="password"
                                   placeholder="Minimal 8 karakter"
                                   required autocomplete="new-password" />
        </x-auth.field>

        <x-auth.field label="Konfirmasi password" for="password_confirmation">
            <x-auth.password-input id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi password"
                                   required autocomplete="new-password" />
        </x-auth.field>

        <x-auth.submit-button>Reset Password</x-auth.submit-button>
    </form>
</x-guest-layout>
