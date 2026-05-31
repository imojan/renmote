@extends('layouts.dashboard')

@section('title', __('Kelola Penyewa'))

@section('content')
@php
    $tabs = [
        'all'     => ['label' => __('Semua').' ('.$stats['all'].')',     'tone' => 'slate'],
        'active'  => ['label' => __('Aktif').' ('.$stats['active'].')',  'tone' => 'success'],
        'deleted' => ['label' => __('Deleted').' ('.$stats['deleted'].')', 'tone' => 'danger'],
    ];
@endphp

<x-dashboard.card padded="false">
    <x-slot name="title">{{ __('Daftar Penyewa') }}</x-slot>
    <x-slot name="subtitle">{{ __('Total :count penyewa terdaftar.', ['count' => $users->total()]) }}</x-slot>
    <x-slot name="actions">
        <div class="flex flex-wrap items-center gap-2">
            @foreach ($tabs as $value => $tab)
                @php $isActive = $filter === $value; @endphp
                <a href="{{ route('admin.users.index', ['filter' => $value, 'q' => $keyword ?: null]) }}"
                   class="inline-flex h-9 items-center rounded-full px-4 text-xs font-semibold transition
                          {{ $isActive
                              ? 'bg-rn-primary text-white shadow-sm'
                              : 'border border-slate-200 bg-white text-slate-600 hover:border-rn-primary/40 hover:text-rn-primary' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </x-slot>

    {{-- Search bar --}}
    <div class="border-b border-slate-100 p-4 sm:p-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <x-dashboard.field label="{{ __('Cari Penyewa') }}" for="q">
                <x-dashboard.input id="q" name="q" :value="$keyword" placeholder="{{ __('Nama, username, email, telepon') }}" />
            </x-dashboard.field>
            <x-dashboard.btn variant="primary" type="submit" icon="fa-magnifying-glass">{{ __('Cari') }}</x-dashboard.btn>
        </form>
    </div>

    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('User') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Kontak') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Aktivitas') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Bergabung') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="font-semibold text-rn-text">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $user->username ? '@'.$user->username : '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="text-rn-text">{{ $user->email }}</div>
                                <div class="text-xs text-slate-500">{{ $user->phone_number ?: '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="text-rn-text">{{ $user->bookings_count }} booking</div>
                                <div class="text-xs text-slate-500">{{ $user->wishlists_count }} wishlist</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-xs text-slate-500">
                                <div>{{ $user->created_at->locale(app()->getLocale())->translatedFormat('d M Y') }}</div>
                                @if($user->deleted_at)
                                    <div class="mt-0.5 text-red-500">{{ __('Hapus') }}: {{ $user->deleted_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <x-dashboard.btn variant="secondary" size="sm" :href="route('admin.users.show', $user->id)" icon="fa-eye">{{ __('Detail') }}</x-dashboard.btn>
                                    @if(!$user->deleted_at)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                              data-confirm-title="{{ __('Hapus user ini?') }}"
                                              data-confirm-message="{{ __('User :name akan dihapus dari platform dan ditandai deleted.', ['name' => $user->name]) }}"
                                              data-confirm-confirm-text="{{ __('Ya, Hapus') }}"
                                              data-confirm-cancel-text="{{ __('Batal') }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-dashboard.btn variant="danger" size="sm" type="submit" icon="fa-trash">{{ __('Hapus') }}</x-dashboard.btn>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">{{ __('Sudah dihapus') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">{{ $users->links() }}</div>
        @endif
    @else
        <x-dashboard.empty icon="fa-users" message="{{ __('Tidak ada user ditemukan.') }}" />
    @endif
</x-dashboard.card>
@endsection
