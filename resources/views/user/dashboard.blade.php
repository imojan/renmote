@extends('layouts.dashboard')

@section('title', __('dashboard.user.page_title'))

@section('content')
    @php
        $userStatusLabels = [
            'pending' => __('dashboard.user.status_pending'),
            'confirmed' => __('dashboard.user.status_confirmed'),
            'completed' => __('dashboard.user.status_completed'),
            'cancelled' => __('dashboard.user.status_cancelled'),
        ];
    @endphp

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-stat-card label="{{ __('dashboard.user.stat_active_bookings') }}" :value="$activeBookings->count()" color="blue">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-stat-card>

        <x-stat-card label="{{ __('dashboard.user.stat_total_bookings') }}" :value="$activeBookings->count() + $bookingHistory->count()" color="green">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </x-stat-card>
    </div>

    <!-- Active Bookings -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">{{ __('dashboard.user.active_bookings_title') }}</h2>
        </div>
        <div class="p-6">
            @if($activeBookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_vehicle') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_dates') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_total') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activeBookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $booking->vehicle->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->vehicle->vendor->store_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->locale(app()->getLocale())->translatedFormat('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($booking->end_date)->locale(app()->getLocale())->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @endif">
                                            {{ $userStatusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('user.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900">{{ __('dashboard.user.action_detail') }}</a>
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="inline ml-2"
                                                data-confirm-title="{{ __('dashboard.user.cancel_confirm_title') }}"
                                                data-confirm-message="{{ __('dashboard.user.cancel_confirm_message') }}"
                                                data-confirm-confirm-text="{{ __('dashboard.user.cancel_confirm_yes') }}"
                                                data-confirm-cancel-text="{{ __('dashboard.user.cancel_confirm_no') }}">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('dashboard.user.action_cancel') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">{{ __('dashboard.user.no_active') }}</p>
            @endif
        </div>
    </div>

    <!-- Booking History -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">{{ __('dashboard.user.history_title') }}</h2>
        </div>
        <div class="p-6">
            @if($bookingHistory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_vehicle') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_dates') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_total') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('dashboard.user.col_status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookingHistory as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $booking->vehicle->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->vehicle->vendor->store_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->locale(app()->getLocale())->translatedFormat('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($booking->end_date)->locale(app()->getLocale())->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($booking->status === 'completed') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                            @endif">
                                            {{ $userStatusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">{{ __('dashboard.user.no_history') }}</p>
            @endif
        </div>
    </div>
@endsection
