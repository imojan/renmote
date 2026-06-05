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
    <body class="font-poppins text-rn-text antialiased">
        <div class="flex min-h-screen flex-col items-center bg-white">
            {{-- Top brand accent stripe --}}
            <div class="h-1 w-full flex-shrink-0 bg-gradient-to-r from-rn-blue via-rn-primary to-rn-accent"></div>

            <div class="mx-auto w-full max-w-[420px] px-5 pb-9 pt-7 sm:px-8 sm:pb-12 sm:pt-10">
                {{-- Logo --}}
                <div class="mb-7 flex justify-center">
                    <a href="/">
                        <img src="{{ asset('images/renmote-biru.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto object-contain">
                    </a>
                </div>

                {{ $slot }}
            </div>

            <div class="w-full max-w-[420px] px-8 pb-8 pt-6 text-center text-xs leading-relaxed text-gray-400">
                {{ __('auth_extra.agree_prefix') }}
                <a href="{{ route('rent.terms') }}" class="text-gray-500 underline underline-offset-2 transition-colors hover:text-rn-text">{{ __('auth_extra.agree_terms') }}</a> {{ __('auth_extra.agree_and') }}
                <a href="{{ route('about') }}#privacy" class="text-gray-500 underline underline-offset-2 transition-colors hover:text-rn-text">{{ __('auth_extra.agree_privacy') }}</a> {{ __('auth_extra.agree_brand') }}
            </div>
        </div>

        {{-- Notification Modal --}}
        <x-notification-modal />

        @stack('scripts')
    </body>
</html>
