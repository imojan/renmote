<x-guest-layout>
    @section('title', __('auth.verify_email'))

    <x-auth.title :subtitle="__('auth.verify_email_subtitle')">
        {{ __('auth.verify_email_title') }}
    </x-auth.title>

    @if (session('status') == 'verification-link-sent')
        <x-auth.alert type="success">{{ __('auth.verify_link_sent') }}</x-auth.alert>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" data-rn-loading>
        @csrf
        <x-auth.submit-button>{{ __('auth.verify_resend') }}</x-auth.submit-button>
    </form>

    <x-auth.footer>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit"
                    class="cursor-pointer border-0 bg-transparent font-poppins text-[0.9375rem] font-bold text-rn-text underline underline-offset-[3px] transition-colors hover:text-rn-primary">
                {{ __('auth.verify_logout') }}
            </button>
        </form>
    </x-auth.footer>
</x-guest-layout>
