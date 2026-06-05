<x-guest-layout>
    @section('title', __('auth.reset_password'))

    <x-auth.title :subtitle="__('auth.reset_password_subtitle')">
        {{ __('auth.reset_password_title') }}
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

        <x-auth.field :label="__('auth.login_email')" for="email">
            <x-auth.input id="email" type="email" name="email"
                          value="{{ old('email', $request->email) }}"
                          required autofocus autocomplete="username" />
        </x-auth.field>

        <x-auth.field :label="__('auth.reset_new_password')" for="password">
            <x-auth.password-input id="password"
                                   :placeholder="__('auth.register_password_placeholder')"
                                   required autocomplete="new-password" />
        </x-auth.field>

        <x-auth.field :label="__('auth.register_password_confirm')" for="password_confirmation">
            <x-auth.password-input id="password_confirmation" name="password_confirmation"
                                   :placeholder="__('auth.register_password_confirm_placeholder')"
                                   required autocomplete="new-password" />
        </x-auth.field>

        <x-auth.submit-button>{{ __('auth.reset_button') }}</x-auth.submit-button>
    </form>
</x-guest-layout>
