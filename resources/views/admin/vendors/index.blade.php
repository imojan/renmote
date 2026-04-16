@extends('layouts.dashboard')

@section('title', 'Kelola Vendor')

@section('sidebar')
    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.vendors.index') }}" :active="request()->routeIs('admin.vendors.*')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Vendor
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.vehicles.index') }}" :active="request()->routeIs('admin.vehicles.*')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Kendaraan
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.bookings.index') }}" :active="request()->routeIs('admin.bookings.*')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Booking
    </x-sidebar-link>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Vendor</h2>
        
        <div class="flex space-x-2">
            <a href="{{ route('admin.vendors.index') }}" 
               class="px-3 py-1 rounded-lg {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Semua
            </a>
            <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" 
               class="px-3 py-1 rounded-lg {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Pending
            </a>
                <a href="{{ route('admin.vendors.index', ['status' => 'approved']) }}" 
                    class="px-3 py-1 rounded-lg {{ request('status') == 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Approved
            </a>
                <a href="{{ route('admin.vendors.index', ['status' => 'rejected']) }}" 
                   class="px-3 py-1 rounded-lg {{ request('status') == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    Rejected
                </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        @if($vendors->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Toko</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wilayah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kendaraan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($vendors as $vendor)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $vendor->store_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $vendor->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $vendor->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $vendor->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vendor->district->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vendor->vehicles->count() }} unit
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($vendor->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($vendor->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($vendor->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-blue-600 hover:text-blue-900">Detail</a>

                                        @if($vendor->status === 'pending')
                                            <form action="{{ route('admin.vendors.verify', $vendor) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                            <button
                                                type="button"
                                                class="text-red-600 hover:text-red-900"
                                                data-reject-trigger="true"
                                                data-vendor-name="{{ $vendor->store_name }}"
                                                data-action-url="{{ route('admin.vendors.unverify', $vendor) }}"
                                            >
                                                Reject
                                            </button>
                                        @endif

                                        <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" class="inline"
                                            data-confirm-title="Hapus vendor ini?"
                                            data-confirm-message="Vendor {{ $vendor->store_name }} akan dihapus permanen beserta data dokumen terkait."
                                            data-confirm-confirm-text="Ya, Hapus"
                                            data-confirm-cancel-text="Batal">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-gray-500">Tidak ada vendor ditemukan.</p>
            </div>
        @endif
    </div>

    <div id="rejectModal" class="fixed inset-0 z-50 hidden">
        <div id="rejectModalOverlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div class="relative min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200 bg-white">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 class="text-2xl font-bold text-slate-800">Tolak Pengajuan Vendor</h3>
                    <button id="closeRejectModalBtn" type="button" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                </div>

                <form id="rejectModalForm" method="POST" class="px-6 py-6 space-y-5">
                    @csrf

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm text-amber-800">
                            Anda akan menolak pengajuan toko:
                            <span id="rejectVendorName" class="font-semibold text-amber-900"></span>
                        </p>
                    </div>

                    <div>
                        <label for="rejectReasonInput" class="block text-sm font-semibold text-slate-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="rejectReasonInput"
                            name="reason"
                            rows="4"
                            required
                            maxlength="1000"
                            class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: Dokumen KTP kurang jelas. Silakan upload ulang dengan resolusi yang lebih baik."
                        ></textarea>
                        <p id="rejectReasonError" class="text-sm text-red-600 mt-2 hidden">Alasan penolakan wajib diisi.</p>
                    </div>

                    <div class="flex justify-end items-center gap-3 pt-2">
                        <button id="cancelRejectModalBtn" type="button" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const rejectModal = document.getElementById('rejectModal');
        const rejectModalOverlay = document.getElementById('rejectModalOverlay');
        const closeRejectModalBtn = document.getElementById('closeRejectModalBtn');
        const cancelRejectModalBtn = document.getElementById('cancelRejectModalBtn');
        const rejectModalForm = document.getElementById('rejectModalForm');
        const rejectVendorName = document.getElementById('rejectVendorName');
        const rejectReasonInput = document.getElementById('rejectReasonInput');
        const rejectReasonError = document.getElementById('rejectReasonError');

        function openRejectModal(actionUrl, vendorName) {
            rejectModalForm.action = actionUrl;
            rejectVendorName.textContent = vendorName;
            rejectReasonInput.value = '';
            rejectReasonError.classList.add('hidden');
            rejectModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            rejectReasonInput.focus();
        }

        function closeRejectModal() {
            rejectModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.querySelectorAll('[data-reject-trigger="true"]').forEach((button) => {
            button.addEventListener('click', () => {
                openRejectModal(button.dataset.actionUrl, button.dataset.vendorName);
            });
        });

        closeRejectModalBtn.addEventListener('click', closeRejectModal);
        cancelRejectModalBtn.addEventListener('click', closeRejectModal);
        rejectModalOverlay.addEventListener('click', closeRejectModal);

        rejectModalForm.addEventListener('submit', (event) => {
            if (!rejectReasonInput.value.trim()) {
                event.preventDefault();
                rejectReasonError.classList.remove('hidden');
                rejectReasonInput.focus();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !rejectModal.classList.contains('hidden')) {
                closeRejectModal();
            }
        });
    </script>
@endsection
