@extends($isFrontLayout ? 'layouts.front' : 'layouts.dashboard')

@section('title', 'Detail Notifikasi')

@section('content')
<section class="section booking-front-section notification-page {{ $isFrontLayout ? 'notification-page-front' : 'notification-page-dashboard' }}">
    @php
        $data = $notification->data ?? [];
        $title = $data['title'] ?? 'Detail Notifikasi';
        $message = $data['message'] ?? '-';
        $actionUrl = $data['action_url'] ?? null;
        $actionLabel = $data['action_label'] ?? 'Buka Halaman Terkait';
    @endphp

    <div class="booking-front-head">
        <div>
            <h2 class="section-title">{{ $title }}</h2>
            <p class="booking-front-subtitle">Diterima {{ $notification->created_at?->format('d M Y H:i') }}</p>
        </div>
        <a href="{{ route('notifications.index') }}" class="booking-back-link">
            <i class="fa fa-arrow-left"></i> Kembali ke notifikasi
        </a>
    </div>

    <article class="notification-detail-card">
        <div class="notification-detail-message">{{ $message }}</div>

        @if($actionUrl)
            <div class="notification-detail-action">
                <a href="{{ $actionUrl }}" class="booking-btn-primary">{{ $actionLabel }}</a>
            </div>
        @endif

        <div class="notification-detail-meta">
            <h4>Data Tambahan</h4>
            <ul>
                @forelse($data as $key => $value)
                    <li>
                        <span>{{ str_replace('_', ' ', ucfirst((string) $key)) }}</span>
                        <strong>
                            @if(is_scalar($value) || is_null($value))
                                {{ is_null($value) ? '-' : (string) $value }}
                            @else
                                {{ json_encode($value) }}
                            @endif
                        </strong>
                    </li>
                @empty
                    <li><span>Tidak ada metadata tambahan.</span></li>
                @endforelse
            </ul>
        </div>
    </article>
</section>
@endsection
