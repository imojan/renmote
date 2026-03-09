{{-- 
    Address Onboarding Modal
    Include this in any layout: @include('components.address-modal')
    Open via JS: document.getElementById('addressModal').classList.remove('hidden')
    Requires $districts to be passed or fetched via Alpine/AJAX
--}}

<div id="addressModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
    {{-- Overlay --}}
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeAddressModal()"></div>

    {{-- Modal --}}
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform rounded-2xl bg-white p-6 shadow-xl transition-all">
            
            {{-- Header --}}
            <div class="mb-6 text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Tambah Alamat</h3>
                <p class="mt-1 text-sm text-gray-500">Lengkapi alamat untuk pengiriman & pengambilan motor.</p>
            </div>

            {{-- Close button --}}
            <button type="button" onclick="closeAddressModal()" class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Form --}}
            <form id="addressForm" method="POST" action="{{ route('user.addresses.store') }}">
                @csrf

                {{-- Label --}}
                <div class="mb-4">
                    <label for="addr_label" class="mb-1 block text-sm font-medium text-gray-700">Label Alamat</label>
                    <div class="flex gap-2">
                        <button type="button" class="label-chip rounded-full border px-4 py-2 text-sm transition-all" data-value="Rumah" onclick="selectLabel(this)">🏠 Rumah</button>
                        <button type="button" class="label-chip rounded-full border px-4 py-2 text-sm transition-all" data-value="Kantor" onclick="selectLabel(this)">🏢 Kantor</button>
                        <button type="button" class="label-chip rounded-full border px-4 py-2 text-sm transition-all" data-value="Kos" onclick="selectLabel(this)">🏘️ Kos</button>
                    </div>
                    <input type="hidden" name="label" id="addr_label" value="" required>
                    <input type="text" id="addr_label_custom" class="mt-2 hidden w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Atau ketik label lain...">
                    @error('label') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Street --}}
                <div class="mb-4">
                    <label for="addr_street" class="mb-1 block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea name="street" id="addr_street" rows="2" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Nama jalan, nomor rumah, RT/RW, detail lainnya..."></textarea>
                    @error('street') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- District & City --}}
                <div class="mb-4 grid grid-cols-2 gap-3">
                    <div>
                        <label for="addr_district" class="mb-1 block text-sm font-medium text-gray-700">Kecamatan</label>
                        <select name="district_id" id="addr_district" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Pilih Kecamatan</option>
                            @isset($districts)
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                        @error('district_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="addr_city" class="mb-1 block text-sm font-medium text-gray-700">Kota</label>
                        <input type="text" name="city" id="addr_city" value="Malang" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Kota">
                        @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Postal Code --}}
                <div class="mb-4">
                    <label for="addr_postal" class="mb-1 block text-sm font-medium text-gray-700">Kode Pos</label>
                    <input type="text" name="postal_code" id="addr_postal" required maxlength="10"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="65xxx">
                    @error('postal_code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Default Toggle --}}
                <div class="mb-6 flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Jadikan alamat utama</p>
                        <p class="text-xs text-gray-500">Digunakan sebagai default saat booking</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" name="is_default" value="1" class="peer sr-only" checked>
                        <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-2 peer-focus:ring-blue-300"></div>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="button" onclick="closeAddressModal()"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Open / Close Modal
    function openAddressModal() {
        document.getElementById('addressModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeAddressModal() {
        document.getElementById('addressModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Label chip selection
    function selectLabel(chip) {
        document.querySelectorAll('.label-chip').forEach(c => {
            c.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600');
            c.classList.add('border-gray-300', 'text-gray-600');
        });
        chip.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600');
        chip.classList.remove('border-gray-300', 'text-gray-600');
        document.getElementById('addr_label').value = chip.dataset.value;
        document.getElementById('addr_label_custom').classList.add('hidden');
    }

    // Custom label — show text input if user clicks "Other" (handled by Alpine or manual)
    document.getElementById('addr_label_custom')?.addEventListener('input', function() {
        document.getElementById('addr_label').value = this.value;
    });

    // Auto-open modal for onboarding (if user has no addresses)
    @auth
        @if(auth()->user()->addresses()->count() === 0)
            document.addEventListener('DOMContentLoaded', () => {
                // Slight delay for smooth page load
                setTimeout(openAddressModal, 500);
            });
        @endif
    @endauth
</script>
