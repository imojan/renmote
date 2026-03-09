<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Renmote') }} - @yield('title', 'Login')</title>
        <link rel="icon" type="image/png" href="{{ asset('images/renmote-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="auth-page">
            <div class="auth-container">
                <!-- Logo -->
                <div class="auth-logo">
                    <a href="/">
                        <img src="{{ asset('images/renmote-logo.png') }}" alt="{{ config('app.name') }}">
                    </a>
                </div>

                {{ $slot }}
            </div>

            <div class="auth-disclaimer">
                Dengan melanjutkan, kamu menyetujui
                <a href="#">Syarat & Ketentuan</a> dan
                <a href="#">Kebijakan Privasi</a> Renmote.
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
