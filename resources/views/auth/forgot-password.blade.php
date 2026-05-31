<x-guest-layout>
    @section('title', 'Lupa Password')

    <x-auth.title subtitle="Masukkan email kamu dan kami akan kirimkan link untuk reset password.">
        Lupa password?
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

    <form method="POST" action="{{ route('password.email') }}" data-rn-loading>
        @csrf

        <x-auth.field label="Email" for="email">
            <x-auth.input id="email" type="email" name="email"
                          value="{{ old('email') }}"
                          placeholder="nama@email.com"
                          required autofocus />
        </x-auth.field>

        <x-auth.submit-button>Kirim Link Reset</x-auth.submit-button>
    </form>

    <x-auth.footer text="Ingat password kamu?">
        <a href="{{ route('login') }}">Kembali ke login</a>
    </x-auth.footer>
</x-guest-layout>
