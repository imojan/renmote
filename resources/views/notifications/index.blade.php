@extends($isFrontLayout ? 'layouts.front' : 'layouts.dashboard')

@section('title', 'Notifikasi')

@section('content')
<section class="section booking-front-section notification-page {{ $isFrontLayout ? 'notification-page-front' : 'notification-page-dashboard' }}">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Notifikasi</h2>
            <p class="booking-front-subtitle">Semua update booking, pembayaran, dan verifikasi akun ada di sini.</p>
        </div>

        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="booking-btn-secondary booking-inline-btn">Tandai Semua Dibaca ({{ $unreadCount }})</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    @if($notifications->count() > 0)
        <div class="notification-list">
            @foreach($notifications as $notification)
                @php
                    $data = $notification->data ?? [];
                    $title = $data['title'] ?? 'Notifikasi Baru';
                    $message = $data['message'] ?? 'Ada pembaruan baru untuk akun Anda.';
                @endphp

                <article class="notification-card {{ is_null($notification->read_at) ? 'is-unread' : '' }}">
                    <div class="notification-card-main">
                        <div class="notification-card-head">
                            <h3>{{ $title }}</h3>
                            <span>{{ $notification->created_at?->diffForHumans() }}</span>
                        </div>
                        <p>{{ $message }}</p>
                    </div>

                    <div class="notification-card-actions">
                        <a href="{{ route('notifications.show', $notification) }}" class="booking-btn-primary">Lihat Detail</a>

                        @if(is_null($notification->read_at))
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="booking-btn-secondary">Tandai Dibaca</button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="notification-pagination">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="booking-empty-state">
            <p>Belum ada notifikasi baru.</p>
        </div>
    @endif
</section>
@endsection
