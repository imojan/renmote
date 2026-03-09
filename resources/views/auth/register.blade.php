<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label :value="__('Daftar Sebagai')" />
            <div class="mt-2 grid grid-cols-2 gap-3">
                <label class="relative flex cursor-pointer rounded-lg border p-4 transition-all
                    {{ old('role', 'user') === 'user' ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-600' : 'border-gray-300 bg-white hover:border-gray-400' }}">
                    <input type="radio" name="role" value="user" class="sr-only peer" {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center w-full text-center">
                        <svg class="w-8 h-8 mb-2 {{ old('role', 'user') === 'user' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-sm font-semibold {{ old('role', 'user') === 'user' ? 'text-blue-600' : 'text-gray-700' }}">Penyewa</span>
                        <span class="text-xs text-gray-500 mt-1">Sewa motor dengan mudah</span>
                    </div>
                </label>
                <label class="relative flex cursor-pointer rounded-lg border p-4 transition-all
                    {{ old('role') === 'vendor' ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-600' : 'border-gray-300 bg-white hover:border-gray-400' }}">
                    <input type="radio" name="role" value="vendor" class="sr-only peer" {{ old('role') === 'vendor' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center w-full text-center">
                        <svg class="w-8 h-8 mb-2 {{ old('role') === 'vendor' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="text-sm font-semibold {{ old('role') === 'vendor' ? 'text-blue-600' : 'text-gray-700' }}">Vendor</span>
                        <span class="text-xs text-gray-500 mt-1">Sewakan motor Anda</span>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="role"]').forEach(r => {
                    const label = r.closest('label');
                    const icon = label.querySelector('svg');
                    const title = label.querySelector('.text-sm.font-semibold');
                    if (r.checked) {
                        label.classList.remove('border-gray-300', 'bg-white', 'hover:border-gray-400');
                        label.classList.add('border-blue-600', 'bg-blue-50', 'ring-2', 'ring-blue-600');
                        icon.classList.remove('text-gray-400');
                        icon.classList.add('text-blue-600');
                        title.classList.remove('text-gray-700');
                        title.classList.add('text-blue-600');
                    } else {
                        label.classList.add('border-gray-300', 'bg-white', 'hover:border-gray-400');
                        label.classList.remove('border-blue-600', 'bg-blue-50', 'ring-2', 'ring-blue-600');
                        icon.classList.add('text-gray-400');
                        icon.classList.remove('text-blue-600');
                        title.classList.add('text-gray-700');
                        title.classList.remove('text-blue-600');
                    }
                });
            });
        });
    </script>
    @endpush
</x-guest-layout>
