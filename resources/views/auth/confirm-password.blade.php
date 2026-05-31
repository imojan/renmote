<x-guest-layout>
    @section('title', 'Konfirmasi Password')

    <x-auth.title subtitle="Ini adalah area aman. Silakan konfirmasi password kamu sebelum melanjutkan.">
        Konfirmasi password
    </x-auth.title>

    @if ($errors->any())
        <x-auth.alert type="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-auth.alert>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" data-rn-loading>
        @csrf

        <x-auth.field label="Password" for="password">
            <x-auth.password-input id="password"
                                   placeholder="Masukkan password"
                                   required autocomplete="current-password" />
        </x-auth.field>

        <x-auth.submit-button>Konfirmasi</x-auth.submit-button>
    </form>
</x-guest-layout>
