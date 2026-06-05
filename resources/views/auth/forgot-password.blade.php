<x-guest-layout>
    @section('title', __('auth.forgot_password'))

    <x-auth.title :subtitle="__('auth.forgot_password_subtitle')">
        {{ __('auth.forgot_password_title') }}
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

        <x-auth.field :label="__('auth.login_email')" for="email">
            <x-auth.input id="email" type="email" name="email"
                          value="{{ old('email') }}"
                          :placeholder="__('auth.register_email_placeholder')"
                          required autofocus />
        </x-auth.field>

        <x-auth.submit-button>{{ __('auth.forgot_send_link') }}</x-auth.submit-button>
    </form>

    <x-auth.footer :text="__('auth.forgot_remember')">
        <a href="{{ route('login') }}">{{ __('auth.forgot_back_login') }}</a>
    </x-auth.footer>
</x-guest-layout>
