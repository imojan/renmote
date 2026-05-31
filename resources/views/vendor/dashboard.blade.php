@extends('layouts.dashboard')

@section('title', __('dashboard.vendor.page_title'))

@section('headerActions')
    <a href="{{ route('vendor.vehicles.create') }}"
       class="inline-flex items-center gap-2 rounded-full bg-rn-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rn-primary-dark">
        <i class="fa fa-plus text-xs"></i>
        {{ __('dashboard.vendor.add_vehicle') }}
    </a>
@endsection

@section('content')
    @php
        $statusBadgeMap = [
            'approved' => ['label' => __('dashboard.vendor.status_verified'), 'color' => 'bg-emerald-100 text-emerald-700'],
            'rejected' => ['label' => __('dashboard.vendor.status_rejected'), 'color' => 'bg-red-100 text-red-700'],
            'pending'  => ['label' => __('dashboard.vendor.status_pending'),  'color' => 'bg-amber-100 text-amber-700'],
        ];
        $statusBadge = $statusBadgeMap[$vendor->status] ?? $statusBadgeMap['pending'];

        $stats = [
            [
                'label' => __('dashboard.vendor.stat_total_vehicles'),
                'value' => $totalVehicles,
                'icon' => 'fa-motorcycle',
                'color' => 'bg-sky-50 text-sky-600',
                'href' => route('vendor.vehicles.index'),
                'sub' => $totalVehicles > 0 ? __('Aktif di katalog') : __('Belum ada motor'),
            ],
            [
                'label' => __('dashboard.vendor.stat_total_bookings'),
                'value' => $totalBookings,
                'icon' => 'fa-clipboard-list',
                'color' => 'bg-emerald-50 text-emerald-600',
                'href' => route('vendor.bookings.index'),
                'sub' => __('Total transaksi tercatat'),
            ],
            [
                'label' => __('dashboard.vendor.stat_pending_bookings'),
                'value' => $pendingBookings,
                'icon' => 'fa-hourglass-half',
                'color' => 'bg-amber-50 text-amber-600',
                'href' => route('vendor.bookings.index', ['status' => 'pending']),
                'sub' => __('Menunggu konfirmasi'),
            ],
            [
                'label' => __('dashboard.vendor.stat_revenue'),
                'value' => 'Rp ' . number_format($revenue, 0, ',', '.'),
                'icon' => 'fa-coins',
                'color' => 'bg-violet-50 text-violet-600',
                'href' => route('vendor.bookings.index'),
                'sub' => __('Akumulasi booking confirmed/completed'),
            ],
        ];
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main column --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- Vendor profile card --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-rn-reveal>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rn-primary/10 text-rn-primary">
                            <i class="fa fa-store text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-rn-text">{{ $vendor->store_name }}</h2>
                            <p class="mt-0.5 flex items-center gap-1.5 text-sm text-slate-500">
                                <i class="fa fa-map-marker-alt text-xs"></i>
                                {{ $vendor->district?->name ?? __('Lokasi belum diatur') }}
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full {{ $statusBadge['color'] }} px-3 py-1.5 text-xs font-semibold">
                        @if($vendor->status === 'approved') <i class="fa fa-check-circle"></i>
                        @elseif($vendor->status === 'rejected') <i class="fa fa-times-circle"></i>
                        @else <i class="fa fa-clock"></i>
                        @endif
                        {{ $statusBadge['label'] }}
                    </span>
                </div>

                @if($vendor->status === 'rejected')
                    <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-bold text-red-700">{{ __('dashboard.vendor.rejected_heading') }}</p>
                        <p class="mt-1 text-sm text-red-700">{{ $vendor->rejection_reason ?: __('dashboard.vendor.rejected_no_reason') }}</p>
                        <a href="{{ route('vendor.register') }}"
                           class="mt-3 inline-flex items-center gap-2 rounded-full bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700">
                            {{ __('dashboard.vendor.rejected_action') }}
                        </a>
                    </div>
                @endif

                @if($vendor->documents->count() > 0)
                    <div class="mt-5 border-t border-slate-100 pt-5">
                        <p class="text-sm font-bold text-rn-text">{{ __('dashboard.vendor.documents_status') }}</p>
                        <div class="mt-3 space-y-3">
                            @foreach($vendor->documents as $document)
                                @php
                                    $docStatusLabels = [
                                        'approved' => ['label' => __('dashboard.vendor.doc_status_approved'), 'cls' => 'bg-emerald-100 text-emerald-700'],
                                        'rejected' => ['label' => __('dashboard.vendor.doc_status_rejected'), 'cls' => 'bg-red-100 text-red-700'],
                                        'pending'  => ['label' => __('dashboard.vendor.doc_status_pending'),  'cls' => 'bg-amber-100 text-amber-700'],
                                    ];
                                    $docStatus = $docStatusLabels[$document->status] ?? $docStatusLabels['pending'];
                                    $docTypeLabels = [
                                        'ktp' => 'KTP',
                                        'permit' => __('Surat Izin Usaha'),
                                        'photo' => __('Foto Toko/Lokasi'),
                                    ];
                                    $docTypeLabel = $docTypeLabels[$document->type] ?? strtoupper($document->type);
                                    $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp
                                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="text-sm font-bold text-rn-text">{{ $docTypeLabel }}</p>
                                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $docStatus['cls'] }}">
                                            {{ $docStatus['label'] }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $document->notes ?: __('dashboard.vendor.document_no_notes') }}
                                    </p>

                                    {{-- Preview thumbnail (image only) --}}
                                    @if($isImage)
                                        <a href="{{ route('documents.vendor.media', $document) }}" target="_blank"
                                           class="mt-3 block max-w-[160px] overflow-hidden rounded-lg border border-slate-200">
                                            <img src="{{ route('documents.vendor.media', $document) }}" alt="{{ $docTypeLabel }}" class="h-24 w-full object-cover">
                                        </a>
                                    @endif

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <a href="{{ route('documents.vendor.media', $document) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-rn-primary hover:underline">
                                            <i class="fa fa-arrow-up-right-from-square text-[10px]"></i>
                                            {{ __('dashboard.vendor.view_document') }}
                                        </a>

                                        @if($document->status === 'rejected')
                                            <button type="button"
                                                    data-resubmit-trigger
                                                    data-resubmit-url="{{ route('vendor.documents.resubmit', $document) }}"
                                                    data-resubmit-type="{{ $docTypeLabel }}"
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-rn-primary px-3 py-1 text-[11px] font-semibold text-white transition hover:bg-rn-primary-dark">
                                                <i class="fa fa-arrow-rotate-left text-[10px]"></i>
                                                {{ __('Upload Ulang') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>

            {{-- Stats grid --}}
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" data-rn-reveal>
                @foreach($stats as $stat)
                    <a href="{{ $stat['href'] }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-rn-primary/30 hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $stat['color'] }}">
                                <i class="fa-solid {{ $stat['icon'] }}"></i>
                            </div>
                            <i class="fa fa-arrow-up-right-from-square text-xs text-slate-300 transition group-hover:text-rn-primary"></i>
                        </div>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-1 text-2xl font-extrabold text-rn-text">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ $stat['sub'] }}</p>
                    </a>
                @endforeach
            </section>

            {{-- Quick actions --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-rn-reveal>
                <h3 class="text-lg font-bold text-rn-text">{{ __('dashboard.vendor.quick_actions') }}</h3>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <a href="{{ route('vendor.vehicles.create') }}"
                       class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 transition hover:border-rn-primary/40 hover:bg-rn-primary/5">
                        <span class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rn-primary/10 text-rn-primary">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="text-sm font-semibold text-rn-text">{{ __('dashboard.vendor.add_vehicle') }}</span>
                        </span>
                        <i class="fa fa-chevron-right text-xs text-slate-400"></i>
                    </a>
                    <a href="{{ route('vendor.bookings.manual.create') }}"
                       class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 transition hover:border-rn-primary/40 hover:bg-rn-primary/5">
                        <span class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                <i class="fa fa-store"></i>
                            </span>
                            <span class="text-sm font-semibold text-rn-text">{{ __('Catat Booking Offline') }}</span>
                        </span>
                        <i class="fa fa-chevron-right text-xs text-slate-400"></i>
                    </a>
                    <a href="{{ route('vendor.bookings.index') }}"
                       class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 transition hover:border-rn-primary/40 hover:bg-rn-primary/5">
                        <span class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                <i class="fa fa-clipboard-list"></i>
                            </span>
                            <span class="text-sm font-semibold text-rn-text">{{ __('dashboard.vendor.view_bookings') }}</span>
                        </span>
                        <i class="fa fa-chevron-right text-xs text-slate-400"></i>
                    </a>
                </div>
            </section>
        </div>

        {{-- Side column --}}
        <aside class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-rn-reveal>
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-rn-text">{{ __('Snapshot') }}</h3>
                    <span class="text-xs text-slate-400">{{ now()->locale(app()->getLocale())->translatedFormat('d M Y') }}</span>
                </div>
                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="flex items-center gap-2"><i class="fa fa-circle text-[6px] text-emerald-500"></i> {{ __('Vendor terverifikasi') }}</span>
                        <span class="font-semibold text-rn-text">{{ $vendor->verified ? __('Ya') : __('Belum') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="flex items-center gap-2"><i class="fa fa-circle text-[6px] text-sky-500"></i> {{ __('Motor di katalog') }}</span>
                        <span class="font-semibold text-rn-text">{{ $totalVehicles }}</span>
                    </li>
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="flex items-center gap-2"><i class="fa fa-circle text-[6px] text-amber-500"></i> {{ __('Pesanan menunggu') }}</span>
                        <span class="font-semibold text-rn-text">{{ $pendingBookings }}</span>
                    </li>
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="flex items-center gap-2"><i class="fa fa-circle text-[6px] text-violet-500"></i> {{ __('Pendapatan tercatat') }}</span>
                        <span class="font-semibold text-rn-text">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                    </li>
                </ul>
            </section>

            <section class="rounded-2xl border border-rn-primary/20 bg-gradient-to-br from-rn-primary to-rn-primary-dark p-6 text-white shadow-sm" data-rn-reveal>
                <h3 class="text-lg font-bold">{{ __('Tips Vendor') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-white/80">
                    {{ __('Konfirmasi booking dalam 1×24 jam dan jaga rating dengan respons cepat di chat. Vendor responsif lebih sering muncul di halaman utama.') }}
                </p>
                <a href="{{ route('help') }}"
                   class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-xs font-semibold backdrop-blur transition hover:bg-white/25">
                    {{ __('Pelajari panduan vendor') }} <i class="fa fa-arrow-right text-[10px]"></i>
                </a>
            </section>
        </aside>
    </div>
@endsection


{{-- Resubmit document modal --}}
<div id="resubmitModal" class="fixed inset-0 z-50 hidden">
    <div id="resubmitModalOverlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-5 py-4">
                <h3 class="text-base font-bold text-rn-text">{{ __('Upload Ulang Dokumen') }}</h3>
                <button id="closeResubmitModalBtn" type="button" class="text-2xl leading-none text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form id="resubmitModalForm" method="POST" enctype="multipart/form-data" class="space-y-4 p-5">
                @csrf
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">{{ __('Jenis dokumen') }}</p>
                    <p id="resubmitDocType" class="text-sm font-bold text-rn-text"></p>
                </div>
                <div>
                    <label for="resubmitFile" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ __('File baru') }} <span class="text-red-500">*</span>
                    </label>
                    <input id="resubmitFile" type="file" name="document" accept="image/jpeg,image/png,application/pdf" required
                           class="block w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-rn-primary/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-rn-primary">
                    <p class="mt-1 text-xs text-slate-400">{{ __('JPG/PNG/PDF, maks 2MB.') }}</p>
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                    <x-dashboard.btn variant="secondary" id="cancelResubmitBtn">{{ __('Batal') }}</x-dashboard.btn>
                    <x-dashboard.btn variant="primary" type="submit" icon="fa-upload">{{ __('Kirim Ulang') }}</x-dashboard.btn>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const modal = document.getElementById('resubmitModal');
    if (!modal) return;

    const overlay = document.getElementById('resubmitModalOverlay');
    const closeBtn = document.getElementById('closeResubmitModalBtn');
    const cancelBtn = document.getElementById('cancelResubmitBtn');
    const form = document.getElementById('resubmitModalForm');
    const docType = document.getElementById('resubmitDocType');

    function openModal(url, typeLabel) {
        form.action = url;
        docType.textContent = typeLabel;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        form.reset();
    }

    document.querySelectorAll('[data-resubmit-trigger]').forEach((btn) => {
        btn.addEventListener('click', () => {
            openModal(btn.dataset.resubmitUrl, btn.dataset.resubmitType);
        });
    });

    overlay.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });
})();
</script>
@endpush
