<x-guest-layout>
    @section('title', 'Verifikasi Email')

    <x-auth.title subtitle="Terima kasih telah mendaftar! Silakan verifikasi email kamu dengan klik link yang telah kami kirim. Belum menerima email?">
        Verifikasi email
    </x-auth.title>

    @if (session('status') == 'verification-link-sent')
        <x-auth.alert type="success">Link verifikasi baru telah dikirim ke email kamu.</x-auth.alert>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" data-rn-loading>
        @csrf
        <x-auth.submit-button>Kirim Ulang Email Verifikasi</x-auth.submit-button>
    </form>

    <x-auth.footer>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit"
                    class="cursor-pointer border-0 bg-transparent font-poppins text-[0.9375rem] font-bold text-rn-text underline underline-offset-[3px] transition-colors hover:text-rn-primary">
                Keluar
            </button>
        </form>
    </x-auth.footer>
</x-guest-layout>
