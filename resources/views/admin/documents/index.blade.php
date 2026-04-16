@extends('layouts.dashboard')

@section('title', 'Arsip Dokumen')

@section('content')
    <div class="dash-card mb-5 overflow-visible">
        <div class="dash-card-header">
            <h3 class="dash-card-title">Arsip Dokumen User & Vendor</h3>
        </div>
        <div class="dash-card-body">
            <form action="{{ route('admin.documents.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 md:items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Role Pengirim</label>
                    <select name="role" class="w-full h-12 rounded-lg border-slate-300 text-sm">
                        <option value="all" {{ $role === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="vendor" {{ $role === 'vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="user" {{ $role === 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full h-12 rounded-lg border-slate-300 text-sm">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Cari User/Vendor</label>
                    <input type="text" name="q" value="{{ $keyword }}" class="w-full h-12 rounded-lg border-slate-300 text-sm" placeholder="Nama, email, toko, telepon">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full h-12 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        Terapkan Filter
                    </button>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-5">
                <div class="rounded-xl border border-slate-200 p-3 bg-white">
                    <p class="text-xs text-slate-500">Pending Vendor</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $summary['pending_vendor_documents'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-3 bg-white">
                    <p class="text-xs text-slate-500">Pending User</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $summary['pending_user_documents'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-3 bg-white">
                    <p class="text-xs text-slate-500">Total Vendor Docs</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $summary['total_vendor_documents'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-3 bg-white">
                    <p class="text-xs text-slate-500">Total User Docs</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $summary['total_user_documents'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(in_array($role, ['all', 'vendor'], true))
        <div class="dash-card mb-5 overflow-visible">
            <div class="dash-card-header">
                <h3 class="dash-card-title">Dokumen Vendor</h3>
            </div>
            <div class="dash-card-body space-y-4">
                @forelse($vendorOwners as $vendor)
                    <div class="rounded-xl border border-slate-200 overflow-visible">
                        <div class="p-4 bg-slate-50 border-b border-slate-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <p class="text-base font-bold text-slate-800">{{ $vendor->store_name }}</p>
                                    <p class="text-sm text-slate-600">{{ $vendor->user->name ?? '-' }} • {{ $vendor->user->email ?? '-' }} • {{ $vendor->district->name ?? '-' }}</p>
                                </div>
                                <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-sm text-blue-600 hover:underline">Buka Detail Vendor</a>
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            @foreach($vendor->documents as $document)
                                <div class="rounded-lg border border-slate-200 p-3">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ strtoupper($document->type) }}</p>
                                            <p class="text-xs text-slate-500">Dikirim {{ $document->created_at->diffForHumans() }}</p>
                                        </div>
                                        <a href="{{ route('documents.vendor.media', $document) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat Dokumen</a>
                                    </div>

                                    <form
                                        action="{{ route('admin.documents.vendors.update', $document) }}"
                                        method="POST"
                                        class="grid grid-cols-1 md:grid-cols-[180px_minmax(0,1fr)_190px] gap-2 items-stretch js-doc-review-form"
                                        data-initial-status="{{ $document->status }}"
                                        data-initial-notes="{{ $document->notes ?? '' }}"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="w-full h-11 rounded-lg border-slate-300 text-sm" required>
                                            <option value="pending" {{ $document->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $document->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $document->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        <input type="text" name="notes" value="{{ $document->notes }}" class="w-full h-11 rounded-lg border-slate-300 text-sm" placeholder="Catatan review (opsional)">
                                        <button type="submit" class="w-full h-11 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" data-review-submit>
                                            Simpan Review
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="dash-empty">
                        <p>Tidak ada dokumen vendor untuk filter saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    @if(in_array($role, ['all', 'user'], true))
        <div class="dash-card overflow-visible">
            <div class="dash-card-header">
                <h3 class="dash-card-title">Dokumen User</h3>
            </div>
            <div class="dash-card-body space-y-4">
                @forelse($userOwners as $user)
                    <div class="rounded-xl border border-slate-200 overflow-visible">
                        <div class="p-4 bg-slate-50 border-b border-slate-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <p class="text-base font-bold text-slate-800">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-600">{{ $user->email }} @if($user->phone_number) • {{ $user->phone_number }} @endif</p>
                                </div>
                                <a href="{{ route('admin.bookings.index', ['q' => $user->name]) }}" class="text-sm text-blue-600 hover:underline">Cek Booking Terkait</a>
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            @foreach($user->userDocuments as $document)
                                <div class="rounded-lg border border-slate-200 p-3">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $document->type === 'ktp' ? 'KTP/KTM' : strtoupper($document->type) }}</p>
                                            <p class="text-xs text-slate-500">Dikirim {{ $document->created_at->diffForHumans() }}</p>
                                        </div>
                                        <a href="{{ route('documents.user.media', $document) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat Dokumen</a>
                                    </div>

                                    <form
                                        action="{{ route('admin.documents.users.update', $document) }}"
                                        method="POST"
                                        class="grid grid-cols-1 md:grid-cols-[180px_minmax(0,1fr)_190px] gap-2 items-stretch js-doc-review-form"
                                        data-initial-status="{{ $document->status }}"
                                        data-initial-notes="{{ $document->notes ?? '' }}"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="w-full h-11 rounded-lg border-slate-300 text-sm" required>
                                            <option value="pending" {{ $document->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $document->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $document->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        <input type="text" name="notes" value="{{ $document->notes }}" class="w-full h-11 rounded-lg border-slate-300 text-sm" placeholder="Catatan review (opsional)">
                                        <button type="submit" class="w-full h-11 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" data-review-submit>
                                            Simpan Review
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="dash-empty">
                        <p>Tidak ada dokumen user untuk filter saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-doc-review-form').forEach((form) => {
            const statusInput = form.querySelector('select[name="status"]');
            const notesInput = form.querySelector('input[name="notes"]');
            const submitButton = form.querySelector('[data-review-submit]');

            if (!statusInput || !notesInput || !submitButton) {
                return;
            }

            const initialStatus = (form.dataset.initialStatus || '').trim();
            const initialNotes = (form.dataset.initialNotes || '').trim();

            const syncState = () => {
                const currentStatus = (statusInput.value || '').trim();
                const currentNotes = (notesInput.value || '').trim();
                const hasChanges = currentStatus !== initialStatus || currentNotes !== initialNotes;

                submitButton.disabled = !hasChanges;
            };

            statusInput.addEventListener('change', syncState);
            notesInput.addEventListener('input', syncState);

            syncState();
        });
    });
</script>
@endpush
