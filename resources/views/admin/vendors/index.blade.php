@extends('layouts.dashboard')

@section('title', __('Kelola Vendor'))

@section('content')
@php
    $selectedStatus = request('status');
    $tabs = [
        ''         => ['label' => __('Semua'),    'tone' => 'slate'],
        'pending'  => ['label' => __('Pending'),  'tone' => 'warning'],
        'approved' => ['label' => __('Approved'), 'tone' => 'success'],
        'rejected' => ['label' => __('Rejected'), 'tone' => 'danger'],
        'deleted'  => ['label' => __('Deleted'),  'tone' => 'slate'],
    ];
@endphp

<x-dashboard.card padded="false">
    <x-slot name="title">{{ __('Daftar Vendor') }}</x-slot>
    <x-slot name="actions">
        <div class="flex flex-wrap items-center gap-2">
            @foreach ($tabs as $value => $tab)
                @php $isActive = ($selectedStatus ?? '') === $value; @endphp
                <a href="{{ route('admin.vendors.index', $value === '' ? [] : ['status' => $value]) }}"
                   class="inline-flex h-9 items-center rounded-full px-4 text-xs font-semibold transition
                          {{ $isActive
                              ? 'bg-rn-primary text-white shadow-sm'
                              : 'border border-slate-200 bg-white text-slate-600 hover:border-rn-primary/40 hover:text-rn-primary' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </x-slot>

    @if($vendors->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Toko') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Pemilik') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Wilayah') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Kendaraan') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($vendors as $vendor)
                        @php
                            $statusTone = $vendor->deleted_at ? 'slate' : match ($vendor->status) {
                                'approved' => 'success',
                                'pending'  => 'warning',
                                'rejected' => 'danger',
                                default => 'slate',
                            };
                            $statusLabel = $vendor->deleted_at ? __('Deleted') : ucfirst($vendor->status);
                        @endphp
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="font-semibold text-rn-text">{{ $vendor->store_name }}</div>
                                <div class="text-xs text-slate-500">{{ $vendor->phone }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="text-rn-text">{{ $vendor->user->name ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $vendor->user->email ?? '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $vendor->district?->name ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $vendor->vehicles->count() }} unit</td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <x-dashboard.badge :tone="$statusTone">{{ $statusLabel }}</x-dashboard.badge>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-right">
                                <div class="inline-flex flex-wrap items-center justify-end gap-2">
                                    @if(!$vendor->deleted_at)
                                        <x-dashboard.btn variant="secondary" size="sm" :href="route('admin.vendors.show', $vendor)" icon="fa-eye">{{ __('Detail') }}</x-dashboard.btn>
                                    @endif

                                    @if($vendor->status === 'pending' && !$vendor->deleted_at)
                                        <form action="{{ route('admin.vendors.verify', $vendor) }}" method="POST" class="inline"
                                              data-confirm-title="{{ __('Setujui vendor?') }}"
                                              data-confirm-message="{{ __('Vendor :store akan ditandai approved dan dapat menerima booking.', ['store' => $vendor->store_name]) }}"
                                              data-confirm-confirm-text="{{ __('Ya, Approve') }}"
                                              data-confirm-cancel-text="{{ __('Batal') }}">
                                            @csrf
                                            <x-dashboard.btn variant="primary" size="sm" type="submit" icon="fa-check">{{ __('Approve') }}</x-dashboard.btn>
                                        </form>
                                        <button type="button"
                                                data-reject-trigger="true"
                                                data-vendor-name="{{ $vendor->store_name }}"
                                                data-action-url="{{ route('admin.vendors.unverify', $vendor) }}"
                                                class="inline-flex items-center justify-center gap-2 rounded-full bg-red-500 px-3.5 text-xs font-semibold text-white shadow-sm transition hover:bg-red-600 h-9">
                                            <i class="fa-solid fa-xmark text-[12px]"></i>
                                            {{ __('Reject') }}
                                        </button>
                                    @endif

                                    @if(!$vendor->deleted_at)
                                        <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" class="inline"
                                              data-confirm-title="{{ __('Hapus vendor ini?') }}"
                                              data-confirm-message="{{ __('Vendor :store akan dihapus beserta akun pemiliknya.', ['store' => $vendor->store_name]) }}"
                                              data-confirm-confirm-text="{{ __('Ya, Hapus') }}"
                                              data-confirm-cancel-text="{{ __('Batal') }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-dashboard.btn variant="danger" size="sm" type="submit" icon="fa-trash">{{ __('Hapus') }}</x-dashboard.btn>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-dashboard.empty icon="fa-store" message="{{ __('Tidak ada vendor ditemukan.') }}" />
    @endif
</x-dashboard.card>

{{-- Reject modal --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden">
    <div id="rejectModalOverlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-center justify-between bg-slate-50 px-6 py-5">
                <h3 class="text-lg font-bold text-rn-text">{{ __('Tolak Pengajuan Vendor') }}</h3>
                <button id="closeRejectModalBtn" type="button" class="text-2xl leading-none text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form id="rejectModalForm" method="POST" class="space-y-5 px-6 py-6">
                @csrf

                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm text-amber-800">
                        {{ __('Anda akan menolak pengajuan toko:') }}
                        <span id="rejectVendorName" class="font-semibold text-amber-900"></span>
                    </p>
                </div>

                <div>
                    <label for="rejectReasonInput" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ __('Alasan Penolakan') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rejectReasonInput" name="reason" rows="4" required maxlength="1000"
                              class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-rn-text placeholder:text-slate-400 transition focus:border-rn-primary focus:outline-none focus:ring-2 focus:ring-rn-primary/15"
                              placeholder="{{ __('Contoh: Dokumen KTP kurang jelas. Silakan upload ulang dengan resolusi yang lebih baik.') }}"></textarea>
                    <p id="rejectReasonError" class="mt-2 hidden text-sm text-red-600">{{ __('Alasan penolakan wajib diisi.') }}</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-dashboard.btn variant="secondary" id="cancelRejectModalBtn">{{ __('Batal') }}</x-dashboard.btn>
                    <x-dashboard.btn variant="danger" type="submit" icon="fa-paper-plane">{{ __('Kirim Penolakan') }}</x-dashboard.btn>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const rejectModal = document.getElementById('rejectModal');
    if (!rejectModal) return;

    const rejectModalOverlay = document.getElementById('rejectModalOverlay');
    const closeBtn = document.getElementById('closeRejectModalBtn');
    const cancelBtn = document.getElementById('cancelRejectModalBtn');
    const form = document.getElementById('rejectModalForm');
    const nameEl = document.getElementById('rejectVendorName');
    const reasonInput = document.getElementById('rejectReasonInput');
    const reasonError = document.getElementById('rejectReasonError');

    function openRejectModal(actionUrl, vendorName) {
        form.action = actionUrl;
        nameEl.textContent = vendorName;
        reasonInput.value = '';
        reasonError.classList.add('hidden');
        rejectModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        reasonInput.focus();
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

    closeBtn.addEventListener('click', closeRejectModal);
    cancelBtn.addEventListener('click', closeRejectModal);
    rejectModalOverlay.addEventListener('click', closeRejectModal);

    form.addEventListener('submit', (event) => {
        if (!reasonInput.value.trim()) {
            event.preventDefault();
            reasonError.classList.remove('hidden');
            reasonInput.focus();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !rejectModal.classList.contains('hidden')) {
            closeRejectModal();
        }
    });
})();
</script>
@endpush
@endsection
