@extends('layouts.dashboard')

@section('title', 'Chat')

@section('content')
    <div class="chat-page-shell">
        @include('chat.panel', ['mode' => 'page', 'startVendorId' => $startVendorId ?? null])
    </div>
@endsection
